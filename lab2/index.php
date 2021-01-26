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
            $ret = '';  // начальное значение контента - пусто
            $f = fopen($base, 'rt');    // открываем файл для записи
            while (!feof()) // пока все строки не прочитаны
                $ret .= fgets($f);  // записываем очередную строку в контент
            fclose($f); // закрываем файл
            return $ret;    // возвращаем содержание всего файла
        }

    ?>

        </body>
    </html>