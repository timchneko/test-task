<?php
	class View {

		private function loadTemplate($filepath) {
			$dir = "../frontend/templates";
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
				#var_dump($this->getPostHTML($post));
				$comments .= $this->getPostHTML($post);
			}
			#var_dump($comments);
			eval("\$template = \"$template\";");
			echo $template;
		}
	}
?>