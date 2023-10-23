<?php

require_once "database_connection.php";
require_once "upserter.php";

$conn = connectToDatabase();
getDataFromFile();

$items_per_page = 10;
$page = isset($_GET['page']) ? $_GET['page'] : 1;
$offset = ($page - 1) * $items_per_page;

$sort = isset($_GET['sort']) ? $_GET['sort'] : 'id';
$sql = "SELECT * FROM users ORDER BY $sort LIMIT $items_per_page OFFSET $offset";
$result = $conn->query($sql);

?>

    <!DOCTYPE html>
    <html>
    <head>
        <title>Пользователи</title>
    </head>
    <body>
    <table>
        <tr>
            <th><a href="?sort=id">ID</a></th>
            <th><a href="?sort=login">Логин</a></th>
            <th><a href="?sort=email">Email</a></th>
            <th><a href="?sort=username">Пароль</a></th>
            <th><a href="?sort=created_at">Дата создания</a></th>
            <th><a href="?sort=updated-at">Дата обновления</a></th>
        </tr>
        <?php
        while ($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . $row['id'] . "</td>";
            echo "<td>" . $row['login'] . "</td>";
            echo "<td>" . $row['email'] . "</td>";
            echo "<td>" . $row['username'] . "</td>";
            echo "<td>" . $row['created_at'] . "</td>";
            echo "<td>" . $row['updated_at'] . "</td>";
            echo "</tr>";
        }
        ?>
    </table>

    <?php
    // Пагинация
    $total_items = $conn->query("SELECT COUNT(*) FROM users")->fetch_row()[0];
    $total_pages = ceil($total_items / $items_per_page);

    echo "<div class='pagination'>";
    for ($i = 1; $i <= $total_pages; $i++) {
        echo "<a href='?page=$i&sort=$sort'>$i</a> ";
    }
    echo "</div>";
    ?>

    </body>
    </html>

<?php
$conn->close();
?>