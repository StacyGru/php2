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
            {
                if ($tag == 'a')    // если искомый тег - <a>
                {
                    $href = '';
                    preg_match('#(.*)href="(.*?)"#i', $v[1], $arr); // ищем в атрибутах тега адрес ссылки
                    if ($arr)   // если успешно найдено
                        $href = $arr[2];    // сохраняем адрес в переменной
                    $ret[$k] = array('href' => $href, 'text' => $v[2]); // возвращаем адрес и текст ссылки
                }
                else    // если ищем любой другой теш
                    $ret[$k] = array ('text' => $v[2]);   // возвращаем текст тега
            }
            echoArray($ret);
            return $ret;    // возвращаем полученный массив
        }

        function echoArray($array)
        {
            for ($i=0; $i < count($array); $i++)
            {
                for ($j=0; $j < count($array[$i]); $j++)
                echo $array[$i][$j];
            }   
        }

        if (!isset($_POST['url']))
            echo '<form method="post" name="get_url" action="">
                <label for="url">Введите ссылку:</label> <input type="text" name="url">
                <input type="submit" value="Анализ">
                </form>';
        else
        {
            $code = getHTMLcode($_POST['url']); // определяем html-код страницы

            // echo htmlentities($code);

            $titles = getALLtag($code, 'title');    // получаем массивы с соответствующими тегами
            $descriptions = getALLtag($code, 'description');
            $keywords = getALLtag($code, 'keywords');
            $h1 = getALLtag($code, 'h1');
            $h2 = getALLtag($code, 'h2');
            $a = getALLtag($code, 'a');

            echo '<b>Результаты анализа</b><br><br>';
            echoArray($a);

            echo '<br><br><form method="post" name="go_back" action="">
                <input type="submit" value="Обратно" name="go_back">
                </form>';
        }

        if (isset($_POST['go_back']))
            unset($_POST['url']);
    ?>

        </body>
    </html>