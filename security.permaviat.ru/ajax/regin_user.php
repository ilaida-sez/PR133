<?php
	session_start();
	include("../settings/connect_datebase.php");
	
	$login = $_POST['login'];
	$password = $_POST['password'];
	
	// ищем пользователя
	$query_user = $mysqli->query("SELECT * FROM `users` WHERE `login`='".$login."'");
	$id = -1;
	
	if($user_read = $query_user->fetch_row()) {
    echo $id;
	} else {
		$mysql->query("INSERT INTO `users`('login', `password`, `img`, `roll`) VALUES ('".$login."', '".$password."', '', a)");

		$query_user = $mysql->query("SELECT * FROM `users` WHERE `login`='".$login."' AND `password`= '".$password."';");
		$user_new = $query_user->fetch_row();
		$id = $user_new[0];

		$photo = $_FILES['photo'];

		$fileInfo = pathinfo($photo['name']);
		$extension = isset($fileInfo['extension']) ? "." . $fileInfo['extension'] : "";

		$allowedExtensions = ['png', '.jpg', '.jpeg'];

		$allowedMimeTypes = ['image/png', 'image/jpeg', 'image/jpg'];
		$fileMimeType = mime_content_type($photo['tmp_name']);

		if(in_array($extension, $allowedExtensions) && in_array($fileMimeType, $allowedMimeTypes)) {
		$filename = "user_{$id}{$extension}";
		$uploadPath = "../img/{$filename}";

		if(!move_uploaded_file($photo['tmp_name'], $uploadPath)) {
		error_log("Не удалось сохранить файл: " . $photo['name']);
		}

		$mysql->query("UPDATE `users` SET `img` = '{$filename}' WHERE `id` = {$id}");
		}

		if($id != -1) $_SESSION['user'] = $id;
		echo $id;
	}
?>