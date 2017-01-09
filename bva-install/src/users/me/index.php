<?php
	include_once $_SERVER['DOCUMENT_ROOT'] . "/includes/db_connect.php";
	include_once $_SERVER['DOCUMENT_ROOT'] . "/includes/functions.php";
	secure_session_start();
	header('Location: ../index.php/' . $_SESSION['username']);
?>