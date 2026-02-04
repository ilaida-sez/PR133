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

		// Сохраняем файл как user_{$id} с оригинальным расширением
		$fileInfo = pathinfo($photo['name']);
		$extension = isset($fileInfo['extension']) ? "." . $fileInfo['extension'] : "";

		// Разрешенные расширения
		$allowedExtensions = ['png', '.jpg', '.jpeg'];

		// Дополнительная проверка MIME-типа
		$allowedMimeTypes = ['image/png', 'image/jpeg', 'image/jpg'];
		$fileMimeType = mime_content_type($photo['tmp_name']);

		// Проверяем входит ли расширение файла в разрешённые
		if(in_array($extension, $allowedExtensions) && in_array($fileMimeType, $allowedMimeTypes)) {
		$filename = "user_{$id}{$extension}";
		$uploadPath = "../img/{$filename}";

		// Перемещаем файл в нужную директорию
		if(!move_uploaded_file($photo['tmp_name'], $uploadPath)) {
		// Ошибка перемещения файла
		error_log("Не удалось сохранить файл: " . $photo['name']);
		}

		// Сохраняем изображение в БД
		$mysql->query("UPDATE `users` SET `img` = '{$filename}' WHERE `id` = {$id}");
		}

		if($id != -1) $_SESSION['user'] = $id; // запоминаем пользователя
		echo $id;
	}
?>