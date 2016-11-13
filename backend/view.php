<?php
	class View {

		private function loadTemplate($filepath) {
			$dir = "../templates";
			$file = $dir . $filepath;
			return addslashes(file_get_contents($file));
		}

		function getPostHTML($post) {
			$template = $this->loadTemplate("/post.html");
			eval("\$template = \"$template\";");
			return $template;
		}

		function show($posts) {
			$template = $this->loadTemplate("/view.html");
			$comments = "";
			foreach ($posts as $post) {
				$comments .= $this->getPostHTML($post);
			}
			eval("\$template = \"$template\";");
			echo $template;
		}
	}
?>