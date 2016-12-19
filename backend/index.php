<?php
	require('backend/db.php');
	require('backend/controller.php');
	require('backend/view.php');
	require('backend/model.php');

	if (empty($_REQUEST)) {
		$controller = new Controller();
		$controller->getIndexPage($_COOKIE);
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
	if (isset($_GET['admin'])) {
		$controller = new Controller();
		$controller->getLoginPage();
		return;
	}
	if (isset($_POST['username']) && isset($_POST['pass'])) {
		$controller = new Controller();
		$controller->login($_POST['username'], $_POST['pass']);
		return;
	}
	if (isset($_POST['edit'])) {
		$id = isset($_POST['id']) ? preg_replace('/\D/', '', $_POST['id']) : "";
		$text = isset($_POST['text']) ? $_POST['text'] : "";
		$controller = new Controller();
		$controller->edit($id, $text, $_COOKIE);
		return;
	}
	if (isset($_POST['confirm'])) {
		$id = isset($_POST['id']) ? preg_replace('/\D/', '', $_POST['id']) : "";
		$controller = new Controller();
		$controller->confirm($id, $_POST['confirm'], $_COOKIE);
		return;
	}
	echo "fail";
	return;
?>