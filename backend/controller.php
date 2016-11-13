<?php
	class Controller {

		function getLastEntries() {
			$db = Connection::getInstance();
			$conn = $db->getConnection();
			$query = "SELECT id FROM posts ORDER BY datetime DESC LIMIT 10";
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

		function getIndexPage() {
			$posts = $this->getLastEntries();
			$view = new View();
			$view->show($posts);
		}
	}
?>