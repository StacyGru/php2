<!DOCTYPE html>
<html lang="ru">
    <head>
        <title>Лабораторная работа №2</title>
        <meta charset="utf-8">
        <style>
            <?php
            require "style.css";
            ?>
            </style>
        <link href="https://fonts.googleapis.com/css?family=Montserrat:400,700&display=swap" rel="stylesheet">
        </head>
    <body>

    <?php

        function getHTMLcode($url)
        {
            $ch = curl_init($url);  // инициируем новый сеанс
            curl_setopt($ch, CURLOPT_HEADER, 0);    // заголовки не передаём
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);    // возвратить содержимое файла (без выполнения кода)
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);    // разрешить перенаправление
            curl_setopt($ch, CURLOPT_TIMEOUT, 10);  // тайм-аут 10 секунд
            
            $ret = curl_exec($ch);  //выполнение запроса
            curl_close($ch);    // завершение сеанса
            return $ret;    // возвращаем содержание всего файла
        }

    ?>

        </body>
    </html>