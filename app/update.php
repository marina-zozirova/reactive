<?php

require_once "database_connection.php";
require_once "upserter.php";

$conn = connectToDatabase();
getDataFromFile(true);

$processed = $conn->query('SELECT COUNT(*) FROM users WHERE updated_at >= created_at')->fetch_row()[0];
echo "Всего обработано $processed\n";


$updated = $conn->query('SELECT COUNT(*) FROM users WHERE created_at <> updated_at')->fetch_row()[0];
echo "Всего обновлено $updated\n";

$subquery = $conn->query('SELECT MAX(updated_at) FROM users')->fetch_assoc()['MAX(updated_at)'];
$subquery = $conn->real_escape_string($subquery);
$conn->query("SELECT COUNT(*) FROM users WHERE updated_at = created_at AND updated_at <> '$subquery'");
$conn->query("DELETE FROM users WHERE updated_at = created_at AND updated_at <> '$subquery'");
echo "Всего удалено $conn->affected_rows записей.";



