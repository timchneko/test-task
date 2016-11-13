<?php
	require('db.php');
	require('controller.php');
	require('view.php');
	require('model.php');

	if (empty($_REQUEST)) {
		$controller = new Controller();
		$controller->getIndexPage();
	}
?>