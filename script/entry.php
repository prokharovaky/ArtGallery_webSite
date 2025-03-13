<?php
ob_start();
include("./head.php");
session_start();
require("./connnect.php");

if ($_SERVER['REQUEST_METHOD']==='POST') {
    $login = isset($_POST['entryLogin']) ? trim($_POST['entryLogin']) : '';
    $password = isset($_POST['entryPassword']) ? trim($_POST['entryPassword']) : '';

    if (empty($login)) {
        $errors['login'] = 'Логин обязателен для заполнения.';
    }
    if (empty($password)) {
        $errors['password'] = 'Пароль обязателен для заполнения.';
    }

    if (empty($errors)) {
       
        $stmt = mysqli_prepare($link, "SELECT user_id, password_hash, salt FROM users WHERE login = ?");
        mysqli_stmt_bind_param($stmt, "s", $login);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if ($result && mysqli_num_rows($result) === 1) {
            $user = mysqli_fetch_assoc($result);
            $salt = $user['salt'];
            $hashedInputPassword = md5(md5($password) . $salt);

            if ($hashedInputPassword === $user['password_hash']) {
                $_SESSION['account'] = $user['user_id'];
                // echo '<script> alert( "Вы успешно вошли в аккаунт!" ); </script>';

                header('Location: catalog.php'); 
                exit;
            } else {
            //     $errors['login'] = 'Неверный логин или пароль.';
                echo '<script> alert( "Пароль не верный!" ); </script>';
            }
        } else {
            // $errors['login'] = 'Неверный логин или пароль.';
            echo '<script> alert( "Вы не вошли в аккаунт!" ); </script>';
        }

        mysqli_stmt_close($stmt);
    }


}

?>
<main class="main_reg">
    <div class="reg_form_container">
        <p class="registration_header">We are glad to see you again!</p>
        <form method="post" id="entryForm">
            <label for="entryLogin">Введите логин:</label>
            <input type="text" name="entryLogin" id="entryLogin" placeholder="Введите ваш логин" required autocomplete="off">
            <?php
                if(!empty($errors['entryLogin']))
                {
                    echo "<p class='error-message'>{$errors['entryLogin']}</p>";
                }
            ?>
            <label for="entryLogin">Введите пароль:</label>
            <input type="password" name="entryPassword" id="entryPassword" placeholder="Введите ваш пароль" required autocomplete="off">
            <div class="buttons">
                <input class="button" type="submit" name="vhod" id="vhod" value="ВОЙТИ">
                <a class="vhodButton" href="./registration.php">ЗАРЕГИСТРИРОВАТЬСЯ</a>
            </div>
        </form>
        </div>
</main>
<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script src="./validation.js"></script>
<?php
include("./footer.html");
?>