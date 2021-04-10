<?php
include "config.php";
include "utils.php";

if (isset($_POST['date']) && $_POST['date'] != "")
{
    $calendar = $_POST['date'];
}
else
{ //начало работы - устанавливается сегодняшняя дата
    $calendar = date("Y-m-d");    
}

$html = file_get_contents("weather.html");
$html = str_replace('$calendar', "$calendar", $html);

$main_html = "";


if (($_POST['date'] != "" && isset($_POST['date']))) //если дата установлена
{
    $list = from_db_to_json($calendar, true); // построчно вытаскиваем значения
    if ($list !== null)
    { //если данный день есть в БД
        $main_html .= "<b><i><h2><center><font  face=\"Book antiqua\">Замеры на " . date("d.m.Y", strtotime($calendar)) . ":</font></b></i></h2></center>";
        $main_html .= '<center><table border=0 id="main_table"><tr>';
        $tbl_header = ';Время;Напряжение,В; Ток,А; Мощность,Вт;Pном;L;T';
        $dat_arr = explode(";", $tbl_header);

        for ($p = 1;$p < count($dat_arr);$p++)
        {
            $main_html .= "<td bgcolor=lightblue><center><b><i>$dat_arr[$p]";
        }
        $main_html .= "</tr>";
        for ($i = 0;$i < count($list);$i++)
        { //цикл по строкам
            $main_html .= "<tr>";
            for ($f = 1;$f < 8;$f++)
            { //цикл по столбцам
                $main_html .= "<td bgcolor=lightblue><center><b><i>" . $list[$i][$f];
            }

            $main_html .= "</tr>";
        }
        $main_html .= "</table></center>";
        $main_html .=  ' <div id="container"></div><script> buildCharts(' . json_encode($list) . '); </script>';

    }
    else
    {
        $main_html .=  '<p><font size="7"  face="Book antiqua">Замеров за эту дату нет</font>';
    }
}
else
{
    $main_html .= '<p><font size="7"  face="Book antiqua">Введите дату</font>';
}
if (isset($_POST['download']))
{
    $attachment_location = $_SERVER["DOCUMENT_ROOT"] . "/".$calendar.".csv";
    if (file_exists($attachment_location)) {

        header($_SERVER["SERVER_PROTOCOL"] . " 200 OK");
        header("Content-Type: text/csv");
        header("Content-Length:".filesize($attachment_location));
        header("Content-Disposition: attachment; filename=".$calendar.".csv");
        readfile($attachment_location);
        die();        
    } else {
        die("Error: File not found.");
    } 




    $filed = $calendar.".csv";
    for ($i = 0;$i < count($list);$i++)
    { //цикл по строкам
        for ($f = 1;$f < 8;$f++)
        { //цикл по столбцам
            $rez .= $list[$i][$f].';';
        }

        $rez .= "\r\n";
    }
    file_put_contents($filed, $rez);

}
$html = str_replace('$main_html', "$main_html", $html);
echo  $html;