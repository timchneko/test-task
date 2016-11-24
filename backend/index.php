<?php
	require('backend/db.php');
	require('backend/controller.php');
	require('backend/view.php');
	require('backend/model.php');

	if (empty($_REQUEST)) {
		$controller = new Controller();
		$controller->getIndexPage();
		return;
	}
	if (isset($_REQUEST['page'])) {
		$page = $_REQUEST['page'];
		$controller = new Controller();
		$controller->getComments($page);
		return;
	}
	if (isset($_POST['submit'])) {
		$username = isset($_POST['username']) ? $_POST['username'] : "";
		$email = isset($_POST['email']) ? $_POST['email'] : "";
		$message = isset($_POST['message']) ? $_POST['message'] : "";
		$image = isset($_POST['image']) ? $_POST['image'] : "";
		$controller = new Controller();
		$controller->post($username, $email, $message, $image);
		return;
	}
?>