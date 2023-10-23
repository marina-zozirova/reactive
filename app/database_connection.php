<?php
function connectToDatabase()
{
    $host = 'mysql';
    $username = 'root';
    $password = 'root';
    $db = 'reactive';
    $port = 3306;

    $conn = new mysqli($host, $username, $password, $db, $port);

    if ($conn->connect_error) {
        die("Ошибка подключения к серверу MySQL: " . $conn->connect_error);
    }

    return $conn;
}


