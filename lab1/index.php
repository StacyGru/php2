<!DOCTYPE html>
<html lang="ru">
    <head>
        <title>Лабораторная работа №1</title>
        <meta charset="utf-8">
        <style>
            <?php
            require "style.css";
            ?>
            </style>
        <link href="https://fonts.googleapis.com/css?family=Montserrat:400,500,700&display=swap" rel="stylesheet">
        </head>
    <body>

    <?php

        session_start();    // подключаем механизм сессий

        if(!isset($_SESSION['user']))   // ЕСЛИ ВХОД ЕЩЁ НЕ ПРОИЗОШЁЛ
        {
            echo '<form name="auth" method="post" action="">
            <label for="login">Введите логин:</label> <input type="text" name="login"></input><br>
            <label for="password">Введите пароль:</label> <input type="password" name="password"></input><br>
            <input type="submit" value="Войти">
            </form>';
        }
        else    // ЕСЛИ ВХОД УСПЕШНО ВЫПОЛНЕН
        {
            echo '<p>Добро пожаловать, '.$_SESSION['user']['name'].'!</p>';
        }

        if (!isset($_SESSION['user']) && isset($_POST['login' ]))  // ЕСЛИ ПЕРЕДАНЫ ДАННЫЕ ДЛЯ ВХОДА
        {
            ... // попытка входа
            if (/*результат успешен*/)
                $_SESSION['user'] = $user;  // сохраняем данные о пользователе
        }

    ?>
        </body>
    </html>