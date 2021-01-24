<?php
    function outdirInfo($name, $path)
    {
        echo '<div>';   // начало блока
        echo 'Каталог '.$name.'<br>';   // выводим имя каталога

        $dir = opengir($path);  // открываем каталог

        while (($file = readdir($dir)) !== false)   // пока элементы каталога не кончились
        {
            if (is_dir($d)) // если элемент каталог
                echo 'Подкатолог '.$d.'<br>';
            else if (is_file($d))   // если элемент файл
                echo 'Файл '.$d.'<br>';
        }
        closedir($dir);
        echo '</div>';  // конец блока
    }
    
    if (/*необходимо изменить данные*/)
    {
        ... // загружаем файл и изменяем данные
    }

    ... // отображаем содержимое каталога

    ... // выводим форму для загрузки файлов
?>