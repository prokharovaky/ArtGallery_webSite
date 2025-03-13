<?php
session_start();
require("./connnect.php");

if ($_SERVER['REQUEST_METHOD']==='POST' && isset($_POST['field'] )&& isset($_POST['value']))
{
    $errors = [];
    $field = $_POST['field'];  
    $value = $_POST['value'];  

    if ($field === 'entryLogin')
    {    
        if (isset($value) && !empty($value)&& $_POST['value']!="") {
            $login = $_POST['value'];
            $loginsFromDBQuery = "SELECT login FROM users WHERE login = '$login'";
            $loginResult = mysqli_query($link, $loginsFromDBQuery);

            if (mysqli_num_rows($loginResult) == 0 && $_POST['value']!=""){
                $errors['entryLogin'] = "Пользователь с таким логином не найден";
            }
        }
    }


    // $login = $_POST["login"];
    // $password = $_POST["password"];
    // $password_again = $_POST["passwordAgain"];
    // $name = $_POST["name"];
    // $phone = $_POST["phone"];
    // $gender = $_POST["gender"];

    if ($field === 'login')
    {    
        if (isset($value) && !empty($value)) {
            $login = $_POST["value"];
            $loginsFromDBQuery = "SELECT login FROM users WHERE login = '$login'";
            $loginResult = mysqli_query($link, $loginsFromDBQuery);

            if (mysqli_num_rows($loginResult) > 0 && $_POST['value']!=""){
                $errors['login'] = "Нет, нет, нет. Так не получится, такой логин уже есть!";
            }
        }
    }

    // if ($field === 'log')
    // {    
    //     $errors['login'] = "Нет, нет, нет. Так не получится, такой логин уже есть!";
    //     if (isset($value) && !empty($value)) {
    //         $login = $_POST['value'];
    //         $loginsFromDBQuery = "SELECT login FROM users WHERE login = '$login'";
    //         $loginResult = mysqli_query($link, $loginsFromDBQuery);

    //         if (mysqli_num_rows($loginResult) > 0){
    //             $errors['login'] = "Нет, нет, нет. Так не получится, такой логин уже есть!";
    //         }
    //     }
    // }

    // $pass = "";

    if ($field === 'password')
    {
        $password = $_POST["value"];
        $passwordPattern = "/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).{8,}$/";
         
        if(!preg_match($passwordPattern, $password) && $password!="")
        {
            $errors['password'] = "Ваш пароль - легкотня! Добавьте символы в разном регистре и цифры."; 
        }
    }


    if ($field === 'passwordAgain') {
        $passwordAgain = $_POST["passwordAgain"];
        $password = $_POST["password"]; 
        if ($password !== $passwordAgain && $passwordAgain!="") {
            $errors['passwordAgain'] = "А вы уверены что пароли совпадают?"; 
        }
    }

    if ($field === 'phone')
    {
        $phonePattern = "/^\+375-(25|44|29)-\d{3}-\d{2}-\d{2}$/";
        $phone = $_POST["value"];

        if(!preg_match($phonePattern, $phone) && $phone != "")
        {
            $errors['phone'] = "Телефон дожен быть в формате +375-YY-XXX-XX-XX";
        }
    }

    // if ($field === 'gender') {
    //     $gender = $_POST["value"];
    //     if (empty($value)) {
    //         $errors['gender'] = 'Выберите ваш гендер';
    //     }
    // }



    if ($field === 'captcha') {
        // $errors['captcha'] = 'Капча введена неверно';
        if (isset($_POST['value']) && $_POST['value'] !== $_SESSION['captcha'] && $_POST['value']!="") {
            $errors['captcha'] = 'Капча введена неверно';
        } else {
            // unset($_SESSION['captcha']);
        }
    }

    if(isset($_POST['reg']))
    {}

    echo json_encode($errors);
    exit;

}

?>