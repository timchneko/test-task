<?php
	class Controller {

		private $postOnPageCount = 10;

		function getEntries($isAdmin) {
			$db = Connection::getInstance();
			$conn = $db->getConnection();
			$where = "";
			if (!$isAdmin) {
				$where = " WHERE isApproved = 1";
			}
			$query = "SELECT id FROM posts {$where} ORDER BY datetime DESC, ID DESC";
			$stmnt = $conn->prepare($query);
			$stmnt->execute();
			$result = $stmnt->fetchAll();
			$posts = array();
			foreach ($result as $entry) {
				$model = new Model();
				$model->load($entry['id']);
				array_push($posts, $model);
			}
			return $posts;
		}

		function getPostCount($isAdmin) {
			$db = Connection::getInstance();
			$conn = $db->getConnection();
			$where = "";
			if (!$isAdmin) {
				$where = " WHERE isApproved = 1";
			}
			$query = "SELECT count(1) postcount FROM posts {$where}";
			$stmnt = $conn->prepare($query);
			$stmnt->execute();
			$result = $stmnt->fetch();
			return $result['postcount'];
		}

		function getIndexPage($cookie) {
			$isAdmin = $this->checkPrivilege($cookie);
			$posts = $this->getEntries($isAdmin);
			$postCount = $this->getPostCount($isAdmin);
			$view = new View($isAdmin);
			echo $view->show($posts, $this->postOnPageCount, $postCount);
		}

		function post($username, $email, $message, $imageData) {
			$model = new Model();
			try {
				$model->setName($username);
				$model->setEmail($email);
				$model->setText($message);
				if ($imageData != '') {
					list($type, $data) 	= explode(';', $imageData);
					list(, $data)		= explode(',', $data);
					list(, $filetype) 	= explode('/', $type);
					if (in_array($filetype, array("png", "jpeg", "gif"))) {
						$data = base64_decode($data);
						$imagePath = 'pics/' . time() . rand() . $filetype;
						file_put_contents($imagePath, $data);
						$model->setImagePath($imagePath);
					}
				}
				$model->save();
			} catch (Exception $ex) {
				echo $ex->getMessage();
				return;
			}
			echo 1;
		}

		function getLoginPage() {
			echo (new View())->getLoginPage();
			return;
		}

		function login($name, $pass) {
			$user = new User();
			try {
				$user->load($name);
				if (!$user->login($pass)) {
					echo 0;
					return;
				}
				$user->setHash();
				$user->save();
				echo $user->getHash();
				return;
			} catch (Exception $ex) {
				echo $ex->getMessage();
				return;
			}
		}

		function checkPrivilege($cookie) {
			try {
				if (!empty($cookie) && isset($cookie['username']) && isset($cookie['hash'])) {
					$user = new User();
					$user->load($cookie['username']);
					return ($cookie['hash'] == $user->getHash());
				}
			} catch (Exception $ex) {}
			return 0;
		}

		function edit($id, $text, $cookie) {
			if (!$this->checkPrivilege($cookie)) {
				return;
			}
			try {
				$model = new Model();
				$model->load($id);
				$model->setText($text);
				$model->setIsChanged(1);
				$model->save();
				echo $model->getText();
			} catch (Exception $ex) {}
			return;
		}

		function confirm($id, $approved, $cookie) {
			if (!$this->checkPrivilege($cookie)) {
				return;
			}
			try {
				$model = new Model();
				$model->load($id);
				$model->setIsApproved($approved);
				$model->save();
				echo 1;
				return;
			} catch (Exception $ex) {}
			echo 0;
			return;
		}
	}
?>