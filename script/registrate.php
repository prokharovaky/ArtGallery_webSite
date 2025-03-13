<?php
session_start();
$host = "localhost"; 
$database = "ArtGallery"; 
$user = "wp_admin"; 
$password = "1234"; 
$link = mysqli_connect($host, $user, $password, $database);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['reg'])) {
    $errors = [];

    $login = $_POST["login"];
    $password = $_POST["password"];
    $password_again = $_POST["passwordAgain"];
    $name = $_POST["name"];
    $phone = $_POST["phone"];
    $gender = $_POST["gender"];

    $loginsFromDBQuery = "SELECT login FROM users WHERE login = '$login'";
    $loginResult = mysqli_query($link, $loginsFromDBQuery);

    if (mysqli_num_rows($loginResult) > 0){
        $errors['login'] = "Нет, нет, нет. Так не получится, такой логин уже есть!";
    }

    $passwordPattern = "/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).{8,}$/";
    if(!preg_match($passwordPattern, $password))
    {
        $errors['password'] = "Ваш пароль - легкотня! Добавьте символы в разном регистре и цифры."; 
    }

    if ($password != $password_again) {
        $errors['passwordAgain'] = "А вы уверены что пароли совпадают?"; 
    }

    $phonePattern = "/^\+375-(25|44|29)-\d{3}-\d{2}-\d{2}$/";

    if(!preg_match($phonePattern, $phone))
    {
        $errors['phone'] = "Телефон дожен быть в формате +375-YY-XXX-XX-XX";
    }

    if ($_POST['captcha'] == $_SESSION['captcha']) {
        // echo "Капча введена правильно!";
    } else {
        $errors['captcha'] = "Капча введена неправильно!";
    }
    unset($_SESSION['captcha']);   



    if (empty($errors)) {

        $salt = mt_rand(100, 999);
        $passwordHashed = md5(md5($password) . $salt);
        $query = "INSERT INTO users (login, password_hash, salt, phone_number, name, user_role, gender) VALUES ('$login', '$passwordHashed', '$salt', '$phone', '$name', 'user', '$gender')";
        mysqli_query($link, $query);

        echo json_encode(['success' => true]);
    } else {
        echo json_encode($errors);
    }

    if (mysqli_query($link, $query)) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['error' => mysqli_error($link)]);
    }
    exit;

}

?>