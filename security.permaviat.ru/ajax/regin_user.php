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
		$mysqli->query("INSERT INTO `users`(`login`, `password`, `img`, `roll`) VALUES ('".$login."', '".$password."', '', 0)");
		
		$query_user = $mysqli->query("SELECT * FROM `users` WHERE `login`='".$login."' AND `password`= '".$password."';");
		$user_new = $query_user->fetch_row();
		$id = $user_new[0];
		
		$photo = $_FILES['photo'];
        
        // Сохраняем файл как user_{$id} с оригинальным расширением
        $fileInfo = pathinfo($photo['name']);
        $extension = isset($fileInfo['extension']) ? "." . $fileInfo['extension'] : "";
        $filename = "user_{$id}{$extension}";
        $uploadPath = "../img/{$filename}";
        
        // Перемещаем файл в нужную директорию
        if(!move_uploaded_file($photo['tmp_name'], $uploadPath)) {
            // Ошибка перемещения файла
            error_log("Не удалось сохранить файл: " . $photo['name']);
        }

		$mysqli->query("UPDATE `users` SET `img` = '$filename' WHERE `id` = $id");

		if($id != -1) $_SESSION['user'] = $id; // запоминаем пользователя
		echo $id;
	}
?>