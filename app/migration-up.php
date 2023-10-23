<?php
require_once 'database_connection.php';
function createTable($db, $tableName)
{
    $conn = connectToDatabase();

    $sql = "CREATE TABLE IF NOT EXISTS $tableName (
        id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        login VARCHAR(256) UNIQUE NOT NULL,
        email VARCHAR(256) NOT NULL,
        username VARCHAR(256) NOT NULL,
        password VARCHAR(256) NOT NULL,
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
        updated_at DATETIME DEFAULT CURRENT_TIMESTAMP
    )";

    if ($conn->select_db($db) === FALSE) {
        die("Ошибка при выборе базы данных: " . $conn->error);
    }

    if ($conn->query($sql) === TRUE) {
        echo "Таблица $tableName успешно создана";
    } else {
        echo "Ошибка при создании таблицы: " . $conn->error;
    }

    $conn->close();
}

createTable("reactive", "users");
?>
