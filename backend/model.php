<?php
	class Model {
		private $id;
		private $name;
		private $email;
		private $text;
		private $datetime;
		private $imagePath;
		private $isApproved = 0;
		private $isChanged = 0;

 		public function load($id = "") {
			if (!is_numeric($id)) {
				throw new Exception('Некорректный id!');
			}
			$db = Connection::getInstance();
			$conn = $db->getConnection();
			$query = "SELECT * FROM posts WHERE id = :id";
			$stmnt = $conn->prepare($query);
			$stmnt->bindValue(':id', $id);
			$stmnt->execute();
			$result = $stmnt->fetch();
			foreach ($result as $key => $value) {
				$this->$key = $value;
			}
   	}

   	public function save() {
			$db = Connection::getInstance();
			$conn = $db->getConnection();
			if (!$this->id) {
				$query = "INSERT INTO posts(name, email, text, imagePath, isApproved, isChanged) "
							."VALUES (:name, :email, :text, :imagePath, :isApproved, :isChanged)";
			} else {
				$query = "UPDATE posts SET name = :name, email = :email, text = :text, imagePath = :imagePath, "
								."isApproved = :isApproved, isChanged = :isChanged)"
								."WHERE id = :id";
			}
			$stmnt = $conn->prepare($query);
			$stmnt->bindValue(':name', $this->name);
			$stmnt->bindValue(':email', $this->email);
			$stmnt->bindValue(':text', $this->text);
			$stmnt->bindValue(':imagePath', $this->imagePath);
			$stmnt->bindValue(':isApproved', $this->isApproved);
			$stmnt->bindValue(':isChanged', $this->isChanged);
			if ($this->id) {
				$stmnt->bindValue(':id', $this->id);
			}
			$conn->beginTransaction();
			$stmt1 = $stmnt->execute();
			if (!$this->id) {
				$this->id = $conn->lastInsertId();
			}
			$conn->commit();
   	}

		public function setName($name) {
			if (strlen($name) > 100) {
				throw new Exception('Имя не может превышать 100 символов!');
			}
			$this->name = $name;
		}

		public function setEmail($email) {
			if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
				throw new Exception('Некорректный email!');
			}
			$this->email = $email;
		}

		public function setText($text) {
			if (strlen($name) > 10000) {
				throw new Exception('Сообщение не может превышать 10000 символов!');
			}
			$this->text = $text;
		}

		public function setId($id) {
			$this->id = $id;
		}

		public function setImagePath($imagePath) {
			$this->imagePath = $imagePath;
		}

		public function getId() {
			return $this->id;
		}

		public function getName() {
			return htmlspecialchars($this->name);
		}

		public function getEmail() {
			return htmlspecialchars($this->email);
		}

		public function getText() {
			return htmlspecialchars($this->text);
		}

		public function getDatetime() {
			return $this->datetime;
		}

		public function getImagePath() {
			return $this->imagePath;
		}

		public function getIsApproved() {
			return $this->isApproved;
		}

		public function getIsChanged() {
			return $this->isChanged;
		}

	}
?>