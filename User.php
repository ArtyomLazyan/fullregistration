<?php

require_once "Db.php";

class User
{
    /* Field validation */
    public static function actionRegister()
    {
        if (isset($_POST['register']))
        {
            $name = $_POST['name'];
            $email = $_POST['email'];
            $password = $_POST['password'];
            $errors = false;

            /*** VALIDATION ***/
            if (!User::checkName($name))
            {
                $errors[] = 'Имя не должно быть короче 2-х символов';
            }
            if (!User::checkEmail($email))
            {
                $errors[] = 'Неправильный email';
            }
            if (!User::checkPassword($password)) {
                $errors[] = 'Пароль не должен быть короче 6-ти символов';
            }
            if (User::checkEmailExists($email)) {
                $errors[] = 'Такой email уже используется';
            }

            /********** REGISTRATION *************/
            if ($errors == false)
            {
               $result = User::register($name, $email, $password);
            }
        }

        // Подключаем вид
        require_once(ROOT . '/registration.php');
        return true;

    }
    public static function actionLogout()
    {
        // Стартуем сессию
        session_start();

        // Удаляем информацию о пользователе из сессии
        unset($_SESSION["user"]);

        // Перенаправляем пользователя на главную страницу
        header("Location: /");
    }

    /* User Registration */
    public static function register($name, $email, $password)
    {
        $password = password_hash($password, PASSWORD_DEFAULT);
        $name = trim($name);
        // Соединение с БД
        $db = Db::getConnection();

        // Текст запроса к БД
        $sql = 'INSERT INTO user (name, email, password) '
            . 'VALUES (:name, :email, :password)';

        // Получение и возврат результатов. Используется подготовленный запрос
        $result = $db->prepare($sql);
        $result->bindParam(':name', $name, PDO::PARAM_STR);
        $result->bindParam(':email', $email, PDO::PARAM_STR);
        $result->bindParam(':password', $password, PDO::PARAM_STR);
        return $result->execute();
    }
    public static function sigIn()
    {
        $email = false;
        $password = false;

        if (isset($_POST['sigin']))
        {
            $email = $_POST['email'];
            $password = $_POST['password'];

            $errors = false;

            Db::getConnection();

            /* Field validation */
            if (!User::checkEmail($email)) {
                $errors[] = 'Неправильный email';
            }
            if (!User::checkPassword($password)) {
                $errors[] = 'Пароль не должен быть короче 6-ти символов';
            }
            $userId = User::checkUserData($email, $password);

            if ($userId == false)
            {
                $errors[] = 'Неправильные данные для входа на сайт';
            }
            else
            {
                User::auth($userId);
                header("Location: /adminpanel.php");
            }
        }

        require_once "sigin.php";
        return true;
    }

    /* Is Guest and Authentification */
    public static function isGuest()
    {
        if (isset($_SESSION['user'])) {
            return false;
        }
        return true;
    }
    public static function auth($userId)
    {
        // Записываем идентификатор пользователя в сессию
        $_SESSION['user'] = $userId;
    }

    /** VALIDATION FUNCTIONS **/
    public static function checkName($name)
    {
        $name = trim($name);
        if(strlen($name) >= 2)
        {
            return true;
        }
        else
            return false;
    }
    public static function checkPassword($password)
    {
        if (strlen($password) >= 6)
        {
            return true;
        }
        return false;
    }
    public static function checkEmail($email)
    {
        $email = trim($email);
        if(filter_var($email, FILTER_VALIDATE_EMAIL))
        {
            return true;
        }
        else
            return false;
    }
    /* if Mail exists then return true */
    public static function checkEmailExists($email)
    {
        $email = trim($email);
        // Соединение с БД
        $db = Db::getConnection();

        // Текст запроса к БД
        $sql = "SELECT COUNT(*) FROM user WHERE email = :email";

        // Получение результатов. Используется подготовленный запрос
        $result = $db->prepare($sql);
        $result->bindParam(':email', $email, PDO::PARAM_STR);
        $result->execute();

        if ($result->fetchColumn())
            return true;
        return false;
    }
    /*  return userId */
    public static function checkUserData($email, $password)
    {
        $db = Db::getConnection();
        $sql = "SELECT * FROM user WHERE email=:email";
        $result = $db->prepare($sql);
        $result->bindParam(":email", $email, PDO::PARAM_INT);
        $result->execute();

        $user = $result->fetch();

        if ($user && password_verify($password, $user["password"]))
            return $user["id"];
        else
            return false;

    }

}
