<?php
include "config.php";
include "utils.php";
if (isset($_POST['date'])  || isset($_POST['date2']) )
{
    if ($_POST['date2']>=$_POST['date1']){
        $calendar = $_POST['date1'];
        $calendar2 = $_POST['date2']; 
    }
    else {
        $calendar = date("Y-m-d");
        $calendar2 = date("Y-m-d");  
        
    }
}
else 
{
    $calendar = date("Y-m-d");
    $calendar2 = date("Y-m-d");    
}
if(isset($_GET['sampling_step']) && $_GET['sampling_step']!=="")
{
    $sampling_step = $_GET['sampling_step'];
}else
{
    $sampling_step ="by_1_hour";
}
$html = file_get_contents("weather.html");
$html = str_replace('$calendar1', "$calendar", $html);
$html = str_replace('$calendar2', "$calendar2", $html);
$main_html = "";


if ((isset($_GET['date']) && $_GET['date'] != "") || ( $calendar!=="")) //если дата установлена
{
    $list = load_from_db($calendar,$calendar2, $sampling_step); // построчно вытаскиваем значения
    if ($list !== null)
    { //если данный день есть в БД
        if ($calendar==$calendar2)
        {//если введен только один день
            $main_html .= "<p class=\"text_effect\" style=\"margin-bottom:3px;font-size: 40px;\">Замеры на " . date("d.m.Y", strtotime($calendar))."</p>";
        }
        else
        {//если диапазон
            $main_html .= "<p class=\"text_effect\" style=\"margin-bottom:3px;font-size: 40px;\">Замеры на " . date("d.m.Y", strtotime($calendar))." - ".date("d.m.Y", strtotime($calendar2))."</p>";

        }
        $main_html .= '<table border=0 id="main_table"><tr>';
        $tbl_header = ';Дата;Время;Скорость ветра, м/c; Напр-е ветра; Атм. давление, гПа;Температура;Отн. влажность, %';         
        $dat_arr = explode(";", $tbl_header);

        for ($p = 1;$p < count($dat_arr);$p++)
        {
            $main_html .= "<td bgcolor=ff9494 class=\"text_effect\" style=\" font-size: 18px;\"><center>$dat_arr[$p]";
        }
        $main_html .= "</tr>";
        $main_html .= "</table>";
        $main_html .= "<button type=\"submit\" name=\"download\" id=\"download\" style=\" margin-top: 15px;\">Загрузить таблицу</button>";  
        $main_html .=  ' <div id="container"></div><script> updateData(' . json_encode($list) . '); </script>';

    }
    else
    {
        $main_html .=  '<p class="text_effect" style=\" align: center;\">Замеров за эту дату нет<p class="font_mpei" style=\" align: center;\">К сожалению, наш сайт существует совсем недавно, поэтому значения за какой-то период могут отсутствовать. Мы стараемся регулярно пополнять наши архивы, чтобы было удобно следить за погодными данными!';
    }
}
else
{
    $main_html .= '<p class="text_effect" style=" padding-top:90px;">Пожалуйста, введите дату';
}

if (isset($_POST['download']))
{
    $filename = $calendar.".csv";
    $fp = fopen($filename, 'w');
    foreach ($list as $fields) {
        fputcsv($fp, $fields);
    }
    fclose($fp);
    $content = file_get_contents($filename);
    header('Content-Type: application/octet-stream');
            header('Content-Disposition: attachment; filename=' . basename($filename));
            header('Content-Length: ' . filesize($filename));
    echo $content;
    unlink($filename);
    exit;
}

$html = str_replace('$main_html', "$main_html", $html);
echo  $html;