<?php
	class Controller {

		private $postOnPageCount = 10;

		function getEntries() {
			$db = Connection::getInstance();
			$conn = $db->getConnection();
			$query = "SELECT id FROM posts ORDER BY datetime DESC, ID DESC";
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

		function getPostCount() {
			$db = Connection::getInstance();
			$conn = $db->getConnection();
			$query = "SELECT count(1) postcount FROM posts";
			$stmnt = $conn->prepare($query);
			$stmnt->execute();
			$result = $stmnt->fetch();
			return $result['postcount'];
		}

		function getIndexPage() {
			$posts = $this->getEntries();
			$postCount = $this->getPostCount();
			$view = new View();
			echo $view->show($posts, $this->postOnPageCount, $postCount);
		}

		function getComments() {
			$posts = $this->getEntries();
			$view = new View();
			echo $view->getComments($posts);
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
					$data = base64_decode($data);
					$imagePath = 'pics/' . time() . rand() . $filetype;
					file_put_contents($imagePath, $data);
					$model->setImagePath($imagePath);
				}
				$model->save();
			} catch (Exception $ex) {
				echo $ex->getMessage();
				return;
			}
			$this->getComments();
		}
	}
?>