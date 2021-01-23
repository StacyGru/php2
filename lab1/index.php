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

        if (!isset($_SESSION['user']) && isset($_POST['login']) && isset($_POST['password']) && $auth = fopen('users.csv', 'rt'))  // ЕСЛИ ПЕРЕДАНЫ ДАННЫЕ ДЛЯ ВХОДА и удалось открыть csv файл
        {   // попытка входа
            while (!feof($auth))    // пока не достигнут конец файла, повторяем:
            {
                $test_user = explode(',', fgets($auth)); // читаем строку и развбиваем в массив
                if (trim($test_user[0])==$_POST['login'])   // если 1 элемент массива совпадает с введённым логином (trim - удаляет пробелы)
                {
                    if (isset($test_user[1]) && trim($test_user[1])==$_POST['password'])    // а 2 элемент с введённым паролем
                    {
                        $_SESSION['user'] = $test_user; // пользователь найден, сохраняем данные
                    }
                    header('Location: /'); // перенаправление на главную страницу сайта
                    exit(); // завершаем работу скрипта
                }
            }
            fclose($auth);  // закрываем файл
        }

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
            echo '<p>Добро пожаловать, '.$_SESSION['user'].'!</p>';
            include 'tree.php';
        }
    ?>
        </body>
    </html>