<?php

require_once "database_connection.php";

$conn = connectToDatabase();

$result = $conn->query("SELECT COUNT(*) as count FROM users");
$row = $result->fetch_assoc();
$count = $row['count'];

$action = ($count > 0) ? "update.php" : "upload.php";

$conn->close();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Загрузка файла</title>
</head>
<body>
<h1>Загрузка файла</h1>
<form action="<?= $action ?>" method="post" enctype="multipart/form-data">
    <input type="file" name="file">
    <input type="submit" value="Загрузить">
</form>
</body>
</html>
