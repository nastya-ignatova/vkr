<?php
function from_db_to_json($calendar){
include "config.php"; //подключаем БД
$query = "SELECT * FROM date_table WHERE date_field='".$calendar."'";
$result = mysqli_query($conn, $query) or die(mysqli_error($conn));

if (mysqli_num_rows($result)>0) {//если файл существует на сервере
    $rows_count =mysqli_num_rows($result);
    $i=0;
    while ($row = mysqli_fetch_array($result)){//создаем массив list  сполями как в БД и присваиваем ему соответсвующие значения
            $list[$i][0] = $row['date_field'];
            $list[$i][1] = $row['time_field'];
            $list[$i][2] = $row['v_field'];
            $list[$i][3] = $row['i_field'];
            $list[$i][4] = $row['p_field'];
            $list[$i][5] = $row['pnom_field'];
            $list[$i][6] = $row['l_field'];
            $list[$i][7] = $row['t_field'];
            $i++;
      }

      $json = json_encode($list, JSON_UNESCAPED_UNICODE);
      return[true, $list, $json, $rows_count];
      
    }
else {
    return(false)     ;

    }
}
//from_db_to_json($_GET["calendar"]);
