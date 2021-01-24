<?php
    function makeLink($name, $path)
    {
        $link = 'viewer.php?filename='.$path.'/'.$name; // формируем адрес ссылки
        echo '<a href=".viewer.php?filename='.UrlEncode($path). // во избежание трактовки некоторых символов как элементов URL
            '/'.$name.'" target = "_blank">Файл '.$name.'</a><br>';   // выводим ссылку
    }
    
    function outdirInfo($name, $path)   // передаём имя каталога и путь к нему
    {
        echo '<div>';   // начало блока
        echo 'Каталог '.$name.'<br>';   // выводим имя каталога
        // echo $path;
        // echo count(scandir($path));
         
        // if ($dir = opendir($path))  // если удалось открыть каталог
        // {
            $dir = opendir($path);
            $count_file = 0;
            // echo count(scandir($path));
            while ((($file = readdir($dir)) != false) && ($count_file < count(scandir($path))))   // пока элементы каталога не кончились
            {
                $count_file++;
                if (is_dir($path.'/'.$file) && $file != '.' && $file != '..') // если элемент каталог
                    outdirInfo($file, $path.'/'.$file);   // выводим его содержимое (рекурсивная функция, повторно вызывает саму себя)
                else if (is_file($path.'/'.$file))   // если элемент файл
                    echo makeLink($file, $path);   // выводим его имя
            }
            closedir($dir);
        // }
        echo '</div>';  // конец блока

    }
    
    if (isset($_FILES['myfilename']))   // если файл был выбран
    {
        if (isset($_FILES['myfilename']['dir-name']))   // и имя было указано
        {
            move_uploaded_file($_FILES['myfilename']['dir-name'],   //загружаем файл
                makeName($_FILES['myfilename']['name']));
            echo 'Файл '.$_fILES['myfilename']['name'].' загружен на сервер';
        }
    }

    echo '<div id="dir_tree">'; // отображаем содержимое каталога
    outdirInfo(basename(__DIR__), getcwd());
    echo '</div>';

    echo '<br><form method="post" enctype="multipart/form-data" action="/tree.php">
    <label for="dir-name">Каталог на сервере</label> <input type="text" name="dir-name" id="dir-name"><br>
    <label for="myfilename">Локальный файл</label> <input type="file" name="filename"><br>
    <input type="submit" value="Отправить файл на сервер">
    '; // выводим форму для загрузки файлов
?>