<?php
    $homepage = file_get_contents('../settings/connect_datebase.php');
    echo htmlspecialchars($homepage);
?>