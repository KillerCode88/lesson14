<?php

session_start();

include("function.php");

$servername = "localhost";
$username = "root";
$password = "";
$pdo = new PDO("mysql:host=$servername;dbname=todo;charset=utf8" , $username , $password);
$pdo->exec("SET NAMES utf8;");


$login = isset($_POST['login']) ? trim($_POST['login']) : false;
$password = isset($_POST['password']) ? $_POST['password'] : false;

$message = "Введите данные для регистрации или авторизуйтесь :";

if (isset($_POST['register'])) {
    if (!empty($login) && !empty($password)) {
        $hashedPassword = md5($password);

        $sql = "SELECT login FROM user WHERE login = ?";
        $stm = $pdo->prepare($sql);
        $stm->execute([
            $login
        ]);

        if (empty($stm->fetchColumn())) {
            $sql = "INSERT INTO user (login, password) VALUES (?, ?)";
            $stm = $pdo->prepare($sql);
            $stm->execute([
                $login,
                $hashedPassword
            ]);

            login($login);
        } else {
            $message = "Имя пользователя занято";
        }
    } else {
        $message = "Ошибка регистрации. Заполните все поля.";
    }
}

if (isset($_POST['sign_in'])) {
    if (!empty($login) && !empty($password)) {
        $hashedPassword = md5($password);

        $sql = "SELECT login FROM user WHERE login = ? AND password = ?";
        $stm = $pdo->prepare($sql);
        $stm->execute([
            $login,
            $hashedPassword
        ]);

        if (!empty($stm->fetchColumn())) {

            login($login);
        } else {
            $message = "Такой пользователь не существует, либо неверный пароль.";
        }
    } else {
        $message = "Ошибка входа. Заполните все поля.";
    }
}

?>

<p><?=$message?></p>

<form method="POST">
    <input type="text" name="login" placeholder="Логин" />
    <input type="password" name="password" placeholder="Пароль" />
    <input type="submit" name="sign_in" value="Вход" />
    <input type="submit" name="register" value="Регистрация" />
</form>