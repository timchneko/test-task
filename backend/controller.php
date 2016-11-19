<?php
	class Controller {

		private $postOnPageCount = 10;

		function getEntries($limit, $offset) {
			$db = Connection::getInstance();
			$conn = $db->getConnection();
			$query = "SELECT id FROM posts ORDER BY datetime DESC LIMIT {$limit} OFFSET {$offset}";
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
			$posts = $this->getEntries($this->postOnPageCount, 0);
			$postCount = $this->getPostCount();
			$view = new View();
			echo $view->show($posts, $this->postOnPageCount, $postCount);
		}

		function getComments($page) {
			$posts = $this->getEntries(10, ($page - 1) * $this->postOnPageCount);
			$view = new View();
			echo $view->getComments($posts);
		}
	}
?>