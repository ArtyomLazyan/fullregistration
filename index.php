<?php
session_start();
require_once "User.php";
define('ROOT', dirname(__FILE__));

require_once "views/layouts/header.php";

if (User::isGuest())
{
    echo "<h2>Hello my guest signIn or Register please!</h2>";
    User::sigIn();
    echo "<h1 style='text-align: center;'>Or</h1>";
    User::actionRegister();
}
else
{
    header("Location: /adminpanel.php");
}

require_once "views/layouts/footer.php";

