<?php
include "config.php";

    $filename = "C:\\Apache24\\htdocs\\weather\\Am_12_2020-07-07.csv";//filename хз какой
    if (file_exists (  $filename )) {//если файл существует на сервере
        $fp = fopen($filename, "r");
        fgetcsv($fp, 0, ";");
        while (($data = fgetcsv($fp, 0, ";")) !== FALSE) {
            $list[] = $data;
        }
 
        $data = File($filename);

        for ($i = 0; $i < count($list); $i++) {//выгружаем все строки из файла в БД

            for ($f = 0; $f < 8; $f++) {        //проход по столбцам
                $out[$f] = $list[$i][$f];
            }
          $query = "INSERT INTO date_table( date_field, time_field, v_field, i_field,p_field,pnom_field,l_field,t_field) VALUES ( '".$out[0]."', '".$out[1]."', ".$out[2].", ".$out[3].",".$out[4].", ".$out[5].", ".$out[6].", ".$out[7].")";
                  $result = mysqli_query($conn, $query) or die(mysqli_error($conn));            
        }
        if ($result == true) {
            echo "<p>день загрузился";

        } else {
            echo "<p>день не загрузился";
        }
    }
else {echo '<p>Замеров за эту дату нет';} //надо сделать return, что данных за эту дату нет
