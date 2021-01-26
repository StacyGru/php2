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
            try // ПОПЫТКА ИСПОЛЬЗОВАНИЯ CURL
            {
                if (!($ch = curl_init($url)))  // инициируем новый сеанс
                    throw new Exception();  // если невохможно то генерируем исключительную ситуацию
                    
               
                curl_setopt($ch, CURLOPT_HEADER, 0);    // заголовки не передаём
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);    // возвратить содержимое файла (без выполнения кода)
                curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);    // разрешить перенаправление
                curl_setopt($ch, CURLOPT_TIMEOUT, 10);  // тайм-аут 10 секунд
                
                $ret = curl_exec($ch);  //выполнение запроса
                curl_close($ch);    // завершение сеанса
                return $ret;    // возвращаем содержание всего файла
            }
            catch (Exception $e)    // ЕСЛИ ИСПОЛЬЗОВАТЬ CURL НЕ УДАЛОСЬ
            {
                return @file_get_contents($url);    // используем стандартную функцию
                // @ -для блокировки вывода сообщений об ошибках
            }
        }

        function getALLtag($text, $tag)
        {
            $pattern = '#<'.$tag.'([\s]+[^>]*|)>(.*?)<\/'.$tag.'>#i';   // формируем фаблон для тега
            /*
                i - игнорировать регистр
                #...# - начало и окончание структуры строки
                т.е. строки <p ... </p>

                в каждом элементе массива $ret содержится другой массив:
                [0] - тег целиком (<p>Текст</p>)
                [1] - атрибуты тега
                [2] - текст тега
            */
            preg_match_all($pattern, $text, $ret, PREG_SET_ORDER);  // получаем массив со строками, где начиается текст внутри тегов
            foreach ($ret as $k => $v)
                $ret[$k] = $v[2];   // записываем текст тегов ([2]) в массив
            return $ret;    // возвращаем полученный массив
        }

    ?>

        </body>
    </html>