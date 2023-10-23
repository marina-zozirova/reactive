<?php

require_once "database_connection.php";

function dropTable($db, $tableName)
{
    $conn = connectToDatabase();

    $checkTableQuery = "SHOW TABLES LIKE '$tableName'";
    $checkResult = $conn->query($checkTableQuery);

    if ($checkResult->num_rows > 0) {
        $dropTableQuery = "DROP TABLE $tableName";
        if ($conn->query($dropTableQuery) === TRUE) {
            echo "Таблица $tableName успешно удалена";
        } else {
            echo "Ошибка при удалении таблицы: " . $conn->error;
        }
    } else {
        echo "Таблица $tableName не существует.";
    }

    $conn->close();
}

dropTable("reactive", "users");
