<?php
	include_once 'db_connect.php';
	include_once 'functions.php';
	if(isset($_POST['username'],$_POST['password'])){
		$username = $_POST['username'];
		$password = $_POST['password'];
		secure_session_start();
		if(login($username, $password, $mysqli) == true){
		  header('Location: ../index.php');
		}
		else {
			header('Location: ../login/index.php?e=' . base64_encode('Login fehlgeschlagen. Falscher Benutzer oder falsches Passwort'));
		}
	}
?>