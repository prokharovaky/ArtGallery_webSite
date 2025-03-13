<?php
include("./head.php");
session_start();
require("./connnect.php");

if ($_SERVER['REQUEST_METHOD']==='POST')
{

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

    if (empty($errors)) {

        $salt = mt_rand(100, 999);
        $passwordHashed = md5(md5($password) . $salt);
        $query = "INSERT INTO users (login, password_hash, salt, phone_number, name, user_role, gender) VALUES ('$login', '$passwordHashed', '$salt', '$phone', '$name', 'user', '$gender')";
        mysqli_query($link, $query);
        echo '<script> alert( "Вы успешно зарегестрировались!" ); </script>';
    } else {
        echo '<script> alert( "Кажется вы допустили ошибки при заполнении формы!" ); </script>';
    }

}
?>

<main class="main_reg">
    <div class="reg_form_container">
        <p class="registration_header">Let's get to know each other better</p>
        <form method="post" id="registrationForm">
            <label for="login">Введите логин:</label>
            <input type="text" name="login" id="login" placeholder="Логин пишите тут" required autocomplete="off">
            <?php
                if(!empty($errors['login']))
                {
                    echo "<p class='error-message'>{$errors['login']}</p>";
                }
            ?>
            <label for="login">Введите пароль:</label>
            <input type="password" name="password" autocomplete="off" id="password" placeholder="Тут должны быть буквы в разном регистре и цифры, а длина не меньше 8" required>
            <?php
                if(!empty($errors['password']))
                {
                    echo "<p class='error-message'>{$errors['password']}</p>";
                }
            ?>
            <label for="login">Повторите пароль:</label>
            <input type="password" name="passwordAgain" id="passwordAgain" placeholder="Да, да! Пароль еще раз!:" autocomplete="off" required>
            <?php
                if(!empty($errors['passwordAgain']))
                {
                    echo "<p class='error-message'>{$errors['passwordAgain']}</p>";
                }
            ?>
            <label for="login">Введите имя:</label>
            <input type="text" name="name" id="name" placeholder="Вася, Петя, Оля, Юля или кто вы?" required>
            <?php
                if(!empty($errors['name']))
                {
                    echo "<p class='error-message'>{$errors['name']}</p>";
                }
            ?>
            <label for="login">Введите номер телефона:</label>
            <input type="text" name="phone" id="phone" placeholder="Мы не будем вам звонить, но это не точно) +375-YY-XXX-XX-XX" required>
            <?php
                if(!empty($errors['phone']))
                {
                    echo "<p class='error-message'>{$errors['phone']}</p>";
                }
            ?>
            <div class="gengerChoose">
                <legend>Кем вы себя идентифицируете?:</legend>
                <div>
                    <input type="radio" name="gender" id="male" value="male" required>
                    <label for="male">Мужчина</label>
                </div>
                <div>
                    <input type="radio" name="gender" id="female" value="female" >
                    <label for="male">Женщина</label>
                </div>
            </div>
            <label>А вы не робот?</label>
            <div class="captcha">
                <img src='captcha.php' id='captcha-image'>
                <!-- <br>&emsp;&emsp; -->
                <a class="regenerateCaptcha" href="javascript:void(0);" onclick="document.getElementById('captcha-image').src='captcha.php?rid=' + Math.random();">Обновить капчу</a>
                <!-- <br>
                <br>&nbsp; -->
                <!-- <br>&emsp;&emsp;&emsp; -->
            </div>
            <input type="text" name="captcha" id="captcha" class="captchaInput"/><br />
            <?php
                if(!empty($errors['captcha']))
                {
                    echo "<p class='error-message'>{$errors['captcha']}</p>";
                }
                // echo "<p class='error-message'>{$errors['captcha']}</p>";
                // echo "Captcha в сессии: " . $_SESSION['captcha'];
            ?>
            <div class="buttons">
            <input class="button" type="submit" name="reg" id="reg" value="ЗАРЕГИСТРИРОВАТЬСЯ">
            <a class="vhodButton" href="./entry.php">Уже есть аккаунт? ВОЙТИ</a>
            </div>
        </form>
    </div>
</main>
<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script src="./validation.js"></script>
<?php
include("./footer.html");
?>