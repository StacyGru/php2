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

        if (!isset($_SESSION['user']) && isset($_POST['login' ]))  // ЕСЛИ ПЕРЕДАНЫ ДАННЫЕ ДЛЯ ВХОДА
        {
            $auth = fopen('users.csv', 'rt'); // открываем csv файл
            if ($auth)  // если удалось открыть
            {
                while (!feof($auth))    // пока не достигнут конец файла, повторяем:
                {
                    $str = fgets($auth);    // читаем строку
                    $test_user = explode(',' $str); // разбиваем строку в массив
                    if (trim($test_user[0])==$_POST['login'])   // если 1 элемент массива совпадает с введённым логином (trim - удаляет пробелы)
                    {
                        if (trim($test_user[1])==$_POST['password'])    // а 2 элемент с введённым паролем
                        {
                            $user = $test_user; // пользователь найден и прошёл аутентификацию
                        }
                        break;  // досрочно завершаем цикл
                    }
                }
                fclose($auth);  // закрываем файл
            }
            if (isset($user))   // если пользователь найден
                $_SESSION['user'] = $user;  // сохраняем данные о пользователе
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
            echo '<p>Добро пожаловать, '.$_SESSION['user']['name'].'!</p>';
            include 'tree.php';
        }
    ?>
        </body>
    </html>