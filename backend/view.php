<?php
	class View {

		private function loadTemplate($filepath, $arrayOfData) {
			$dir = "../templates/";
			$file = $dir . $filepath;
			extract($arrayOfData);
			ob_start();
			include($file);
			$html = ob_get_contents();
			ob_end_clean();
			return $html;
		}

		function getComments($posts) {
			$comments = "";
			foreach ($posts as $post) {
				$comments .= $this->loadTemplate("post.html", array("post" => $post));
			}
			return $comments;
		}

		function show($posts, $postOnPageCount, $postCount) {
			$comments = $this->getComments($posts);
			$paginator = "<li class='active pageIndex index1'><a href='#'>1</a></li>";
			for ($i = $postOnPageCount; $i < $postCount; $i += $postOnPageCount) {
				$pageIndex = intdiv($i, $postOnPageCount) + 1;
				$paginator .= "<li class='pageIndex index{$pageIndex}'><a href='#'>{$pageIndex}</a></li>";
			}
			$html = $this->loadTemplate("view.html", array("paginator" => $paginator, "comments" => $comments));
			return $html;
		}
	}
?>