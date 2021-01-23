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

        if (isset($_GET['logout']))
        {
            session_destroy();  // удаляем инфу о пользователе
            header('Location: index.php/'); // перенаправление на главную страницу
            exit();
        }

        if (!isset($_SESSION['password']) && isset($_POST['login']) && isset($_POST['password']) && $auth = fopen('users.csv', 'rt'))  // ЕСЛИ ПЕРЕДАНЫ ДАННЫЕ ДЛЯ ВХОДА и удалось открыть csv файл
        {   // попытка входа
            while (!feof($auth))    // пока не достигнут конец файла, повторяем:
            {
                $test_user = explode(';', fgets($auth)); // читаем строку и развбиваем в массив
                if (trim($test_user[0])==$_POST['login'])   // если 1 элемент массива совпадает с введённым логином (trim - удаляет пробелы)
                {
                    $_SESSION['login'] = $test_user[0];
                    if ((isset($test_user[1]) && trim($test_user[1])==$_POST['password']))    // а 2 элемент с введённым паролем
                    {
                        $_SESSION['password'] = $test_user[1]; // пользователь найден, сохраняем данные
                        header('Location: index.php/'); // перенаправление на главную страницу сайта
                        exit(); // завершаем работу скрипта
                    }
                    else
                        break;
                }   
            }
            echo '<div>Неверный логин или пароль!</div>';
            fclose($auth);  // закрываем файл
        }

        if (!isset($_SESSION['password']))   // ЕСЛИ ВХОД ЕЩЁ НЕ ПРОИЗОШЁЛ
        {

            echo '<form name="auth" method="post" action="">
            <label for="login">Введите логин:</label> <input type="text" name="login"';
            if (isset($_POST['login'])) // если логин был введён верно
                echo ' value="'.$_POST['login'].'"';    // сохраняем значение поля
            if (isset($_COOKIE['user_login']))  // если логин сохранён в куки
                echo ' value="'.$_COOKIE['user_login'].'"';
            echo '></input><br><label for="password">Введите пароль:</label> <input type="password" name="password"';
            if (isset($_COOKIE['user_password']))   // если пароль сохранён в куки
                echo ' value="'.$_COOKIE['user_password'].'"';
            echo '></input><br>
            <input type="submit" value="Войти">
            </form>';
        }
        else    // ЕСЛИ ВХОД УСПЕШНО ВЫПОЛНЕН
        {
            echo '<a href="index.php/?logout=">Выход</a>';
            echo '<p>Добро пожаловать, '.$_SESSION['login'].'!</p>';
            include 'tree.php';
            setcookie("user_login", $_SESSION['login'], strtotime("+30 days"));
            setcookie("user_password", $_SESSION['password'], strtotime("+30 days"));
        }
    ?>
        </body>
    </html>