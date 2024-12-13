<?php
header('Content-Type: text/html; charset=utf-8');

$db_server = "localhost";
$db_user = "root";
$db_pass = "";
$db_name = "books";

$connect = mysqli_connect($db_server, $db_user, $db_pass, $db_name);
if (!$connect) {
    die("Connection failed: " . mysqli_connect_error());
}

if (isset($_POST['search'])) {
    $name = isset($_POST['name']) ? trim(strip_tags($_POST['name'])) : '';
    $author = isset($_POST['author']) ? trim(strip_tags($_POST['author'])) : '';
    $genre = isset($_POST['genre']) ? trim(strip_tags($_POST['genre'])) : '';
    $year = isset($_POST['year']) ? trim(strip_tags($_POST['year'])) : '';
    $query = "SELECT * FROM books WHERE 1=1";
    
    if ($name) {
        $query .= " AND Name LIKE ?";
    }
    if ($author) {
        $query .= " AND Author LIKE ?";
    }
    if ($genre) {
        $query .= " AND Genre LIKE ?";
    }
    if ($year) {
        $query .= " AND Year_publication LIKE ?";
    }
    
    $stmt = $connect->prepare($query);
    $params = [];
    $types = "";
    
    if ($name) {
        $params[] = "%$name%";
        $types .= "s";
    }
    if ($author) {
        $params[] = "%$author%";
        $types .= "s";
    }
    if ($genre) {
        $params[] = "%$genre%";
        $types .= "s";
    }
    if ($year) {
        $params[] = "%$year%";
        $types .= "s";
    }
    if (!empty($params)) {
        $stmt->bind_param($types, ...$params);
    }
    
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        echo "
        <table border='1' cellpadding='10'>
            <thead>
                <tr>
                    <th>Название</th>
                    <th>Автор</th>
                    <th>Жанр</th>
                    <th>Год издания</th>
                    <th>Издательство</th>
                </tr>
            </thead>
            <tbody>";
        
        while ($book = $result->fetch_assoc()) {
            echo "
            <tr>
                <td>{$book['Name']}</td>
                <td>{$book['Author']}</td>
                <td>{$book['Genre']}</td>
                <td>{$book['Year_publication']}</td>
                <td>{$book['Publishing']}</td>
            </tr>";
        }
        
        echo "
            </tbody>
        </table>";
    } else {
        echo "<p class='error'>Информация о книге не найдена.</p>";
    }
}
?>