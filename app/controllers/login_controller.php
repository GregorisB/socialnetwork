<?php
if (!defined('social')) {
	die('Access denied');
}

	$title = 'Login';
	$styles = '';
	$error = '';

	if(isset($_POST['login-btn'])){
		$sql = 'SELECT id, fullname FROM users WHERE username = ?';
		$params = array($_POST['username']);
		$row = $db->row($sql, $params);
		
		if(isset($row->id)){
			$sql = 'SELECT `user_id`, "password" FROM users_login WHERE `user_id` = ?';
			$params = array($row->id);
			$row = $db->row($sql, $params);

			if(password_verify($_POST["pwd"],$row->password)){
				session_start();
				$_SESSION['user'] = $_POST['username'];
				$_SESSION["userId"] = $row->user_id;
				header("Location: ".$appURL."homepage");
				exit();
			}
			else {
				$error = 'Wrong password.';
			}
		}
		else {
			$error = 'The user not found.';
		}
	}
	$err = $error;
	include($appView.'login_view.php');
?>