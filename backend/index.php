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
	if (isset($_POST['preview'])) {
		if (!isset($_POST['username']) || !isset($_POST['email']) || !isset($_POST['message'])) {
			echo "Введены не все данные!";
			return;
		}
		$controller = new Controller();
		$controller->getPreview($_POST['username'], $_POST['email'], $_POST['message']);
		return;
	}
?>