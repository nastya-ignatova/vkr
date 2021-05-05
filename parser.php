<?php
include "config.php";

    $filename = "Am_12_2021-04-30.csv";//filename хз какой
    if (file_exists (  $filename )) {//если файл существует на сервере
        $fp = fopen($filename, "r");
        fgetcsv($fp, 0, ";");
        while (($data = fgetcsv($fp, 0, ";")) !== FALSE) {
            $list[] = $data;
        }
 
        $data = File($filename);

        for ($i = 0; $i < count($list); $i++) {//выгружаем все строки из файла в БД

            for ($f = 0; $f < 7; $f++) {        //проход по столбцам
                $out[$f] = $list[$i][$f];
            }
          $query = "INSERT INTO date_table( date_field, time_field, wind_speed, wind_direction,pressure,t,humidity) VALUES ( '".$out[0]."', '".$out[1]."', ".$out[2].", ".$out[3].",".$out[4].", ".$out[5].", ".$out[6].")";
                  $result = mysqli_query($conn, $query) or die(mysqli_error($conn));            
        }
        if ($result == true) {
            echo "<p>день загрузился";

        } else {
            echo "<p>день не загрузился";
        }
    }
else {echo '<p>Замеров за эту дату нет';} //надо сделать return, что данных за эту дату нет
