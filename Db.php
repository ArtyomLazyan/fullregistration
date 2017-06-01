<?php

/**
 * Устанавливает соединение с базой данных
 * @return \PDO <p>Объект класса PDO для работы с БД</p>
 */

class Db
{
    public static function getConnection()
    {
        $hostname = 'localhost';
        $dbname = 'registration';
        $user = 'root';
        $password = '';

        try {
            $dsn = "mysql:host={$hostname};dbname={$dbname}";
            $db = new PDO($dsn, $user, $password);
            $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $db->exec("set names utf8");

            return $db;

        }
        catch (PDOException $e)
        {
            echo "Connection failed: " . $e->getMessage();
        }
    }
}