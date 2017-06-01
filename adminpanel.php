<?php
session_start();

    $userId = $_SESSION['user'];
    echo "<h1>Hello user who's id {$userId}</h1>";
?>
<h2><a href="/logout.php">SIgn Out</a></h2>

