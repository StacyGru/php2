<?php
    session_start();

    if (!isset($_SESSION['login']))
    {
        echo 'Необходима авторизация';
        exit;
    }
    $f = fopen($_GET['filename'], 'rt');
    if ($f)
    {
        $content = '';
        while (!feof($f))
            $content .= fgets($f);
        echo $content;
        fclose($f);
    }
    else
        echo 'Ошибка открытия файла'.$_GET['filename'];
?>