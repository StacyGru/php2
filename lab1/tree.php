<?php
    echo "<link rel='stylesheet' href='style.css'>";

    if (session_status() != 2)
        session_start();
    
    if (!isset($_SESSION['login']))
    {
        echo 'Необходима авторизация';
        exit;
    }

    function makeLink($name, $path)
    {
        echo '<a href="viewer.php?filename='.UrlEncode($path). // во избежание трактовки некоторых символов как элементов URL
            '/'.$name.'" target="_blank">Файл "'.$name.'"</a><br>';   // выводим ссылку
    }
    
    function outdirInfo($name, $path)   // передаём имя каталога и путь к нему
    {
        echo '<div>';   // начало блока
        echo '<b>Каталог "'.$name.'"</b><br>';   // выводим имя каталога
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

    function makeName($filename)
    {
        if (!file_exists($_POST['dir_name']))
        {
            umask(0);
            mkdir($_POST['dir_name'], 0777, true);
        }
        $tmp = explode('.', $filename);
        $ext = end($tmp);

        $n = 1;
        while (file_exists($_POST['dir_name'].'/'.$n.'.'.$ext))
            $n++;
        updateFileList($_POST['dir_name'].'/'.$n.'.'.$ext);    
        return($_POST['dir_name'].'/'.$n.'.'.$ext);
    }

    function deleteCatalog($dir)   // функция для удаления всех файлов внутри каталога
    {
        if (!file_exists($dir)) {
            return true;
        }
    
        if (!is_dir($dir)) {
            return unlink($dir);
        }
    
        foreach (scandir($dir) as $item) {
            if ($item == '.' || $item == '..') {
                continue;
            }
    
            if (!deleteCatalog($dir.'/'.$item)) {
                return false;
            }
    
        }
    
        return rmdir($dir);
    }

    function updateFileList($filename)
    {
        $info = file('users.csv');    // читаем все строки файла в массив
        $f = fopen('users.csv', 'wt'); // открываем файл для записи
        flock($f, LOCK_EX); // блокируем файл для чтения другими процессами 
        foreach ($info as $k => $user)  // для всех строк массива
        {
            $data = str_getcsv($user, ';'); // декодируем данные
            if ($data[0] == $_SESSION['login'])   // если пользователь найден
                $user .= ';'.$filename;   // добавляем к его файлам новый
            fputs($f, $user);   // сохраняем данные в файл
        }
        flock($f, LOCK_UN); // снимаем блокировку
        fclose($f); // закрываем файл
    }

    // if ((isset($_POST['dir_name'])) && (!isset($_FILES['myfilename'])))    
        

    if (isset($_FILES['myfilename']['tmp_name']))   //  если файл был выбран и имя было указано
    {
        if ($_FILES['myfilename']['tmp_name'])  // если файл существует
        {
            move_uploaded_file($_FILES['myfilename']['tmp_name'],   //загружаем (копируем) файл
                makeName($_FILES['myfilename']['name']));
                
            echo 'Файл '.$_FILES['myfilename']['name'].' загружен на сервер<br><br>';
        }
        else
            deleteCatalog(realpath($_POST['dir_name']));  // удаляем каталог
    }

    // if (isset($_FILES['tmp_name']) && (!isset($_FILES['myfilename'])))
    // {
    //     deleteCatalog($_POST['dir_name'], realpath($_POST['dir_name']));  // удаляем каталог
    // }

    echo '<div id="dir_tree">'; // отображаем содержимое каталога
    outdirInfo(basename(__DIR__), getcwd());
    echo '</div>';

    echo '<br><form method="post" enctype="multipart/form-data" action="">
    <label for="dir_name">Каталог на сервере</label> <input type="text" name="dir_name" id="dir_name"><br>
    <label for="myfilename">Локальный файл</label> <input type="file" name="myfilename"><br>
    <input type="submit" value="Отправить файл на сервер">'; // выводим форму для загрузки файлов
?>