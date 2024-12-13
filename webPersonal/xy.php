<?php

header('Content-Type: text/html;charset=utf-8');

$db_server = "localhost";
$db_user = "root";
$db_pass = "";
$db_name = "users";

$connect = mysqli_connect($db_server, $db_user, $db_pass, $db_name);

if (!$connect) {
    die("Connection failed: " . mysqli_connect_error());
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $surname = trim(strip_tags($_POST['userSurName']));
    $name = trim(strip_tags($_POST['userName']));
    $date_birthday = trim(strip_tags($_POST['userDate']));
    $mail = trim(strip_tags($_POST['userEmail']));
    $login = trim(strip_tags($_POST['userLogin']));
    $password = trim(strip_tags($_POST['userPassword']));

    if (!empty($surname) && !empty($name) && !empty($date_birthday) &&
        !empty($mail) && !empty($login) && !empty($password) && filter_var($mail, FILTER_VALIDATE_EMAIL)) {

        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        $check_login_sql = "SELECT Id FROM users WHERE userLogin = '$login'";
        $result = mysqli_query($connect, $check_login_sql);

        if (mysqli_num_rows($result) > 0) {
            echo json_encode(['status' => 'error', 'message' => 'Этот логин уже занят. Пожалуйста, выберите другой.']);
        } else {
            $sql = "INSERT INTO users (userSurName, userName, userDate, userEmail, userLogin, userPassword)
                    VALUES ('$surname', '$name', '$date_birthday', '$mail', '$login', '$hashed_password')";

            if (mysqli_query($connect, $sql)) {
                $user_id = mysqli_insert_id($connect); 

                if ($user_id) {
                    echo json_encode(['status' => 'success', 'message' => 'Данные успешно внесены в систему! ID пользователя: ' . $user_id]);

                } else {
                    echo json_encode(['status' => 'error', 'message' => 'Ошибка при добавлении пользователя в базу данных.']);
                }
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Ошибка при сохранении данных в базе: '. mysqli_error($connect)]);
            }
        }

    } else {
        echo json_encode(['status' => 'error', 'message' => 'Пожалуйста, заполните все поля и укажите корректный email.']);
    }
}

mysqli_close($connect);
?>