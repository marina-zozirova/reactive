<?php

require_once "database_connection.php";

function getDataFromFile($update = false)
{
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $file = $_FILES['file'];
        if ($file['error'] === UPLOAD_ERR_OK) {
            $exportFile = $file['tmp_name'];
            if ($file['type'] == "text/csv") {

                if (($handle = fopen($exportFile, 'r')) !== false) {
                    $header = fgetcsv($handle, 1000, ';');
                    $loginIndex = array_search('login', $header);
                    $passwordIndex = array_search('password', $header);

                    while (($data = fgetcsv($handle, 1000, ';')) !== false) {
                        $login = isset($data[$loginIndex]) ? $data[$loginIndex] : '';
                        $password = isset($data[$passwordIndex]) ? $data[$passwordIndex] : '';
                        if (!empty($login) && !empty($password)) {
                            insertData($update, $login, $password);
                        }
                    }
                    fclose($handle);
                }
            } else if ($file['type'] == "text/xml") {
                $xml = simplexml_load_file($exportFile);
                foreach ($xml->children() as $user) {
                    $login = (string)$user->login;
                    $password = (string)$user->password;
                    if (!empty($login) && !empty($password)) {
                        insertData($update, $login, $password);
                    }
                }
            } else {
                echo 'Подобный формат данных не поддерживается';
            }
        } else {
            echo 'Ошибка при загрузке файла';
        }
    }
}

function insertData($update, $login, $password)
{
    $conn = connectToDatabase();

    $query = 'INSERT INTO users (`login`, `email`, `username`, `password`) VALUES(?, ?, ?, ?)';
    if ($update) {
        $query = 'INSERT INTO users (`login`, `email`, `username`, `password`, `updated_at`) 
          VALUES (?, ?, ?, ?, CURRENT_TIMESTAMP)
          ON DUPLICATE KEY UPDATE 
          `email` = VALUES(`email`), 
          `username` = VALUES(`username`), 
          `password` = VALUES(`password`),
          `updated_at` = CURRENT_TIMESTAMP';
    }

    $stmt = $conn->prepare($query);
    $email = $login . '@example.com';
    $stmt->bind_param("ssss", $login, $email, $login, $password);
    if (!$stmt->execute()) {
        echo "Ошибка при вставке данных: " . $conn->error;
    }
    $stmt->close();
    $conn->close();
}