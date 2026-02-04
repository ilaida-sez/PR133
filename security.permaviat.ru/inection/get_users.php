<?php
    $users_data = file_get_contents('../settings/connect_datebase.php');
    echo htmlspecialchars($users_data);
?>