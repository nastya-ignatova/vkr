<?php
function from_db_to_json($calendar, $is_hours)
{
    include "config.php"; //подключаем БД
    $query = "SELECT * FROM date_table WHERE date_field='" . $calendar . "'";
    $result = mysqli_query($conn, $query) or die(mysqli_error($conn));

    if (mysqli_num_rows($result) > 0)
    { //если файл существует на сервере
        $rows_count = mysqli_num_rows($result);
        $i = 0;
        while ($row = mysqli_fetch_array($result))
        { //создаем массив list  сполями как в БД и присваиваем ему соответсвующие значения
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

        /* if ($is_hours==true) {
        
          $table_by_hour = array();
          $table_by_hour[0]=$list[0];
              for ($i=1;$i<count($list);$i++){
                  if (Date("H", mktime(end($table_by_hour)[1]))!=Date("H", mktime($list[$i][1])))
                      {
                          $table_by_hour[] =$list[$i];
                      }
                  }
                  $json = json_encode($table_by_hour, JSON_UNESCAPED_UNICODE);
        return[true, $table_by_hour, $json, 24];
        }  else {
        
            $json = json_encode($list, JSON_UNESCAPED_UNICODE);
            return[true, $list, $json, $rows_count];
            }*/

        if ($is_hours == false)
        {

            $json = json_encode($list, JSON_UNESCAPED_UNICODE);
            return [true, $list, $json, $rows_count];
        }
        else
        {

            $table_by_hour = array();
            $table_by_hour[0] = $list[0];
            for ($i = 1;$i < count($list);$i++)
            {
                if (Date("H", mktime(end($table_by_hour) [1])) != Date("H", mktime($list[$i][1])))
                {
                    $table_by_hour[] = $list[$i];
                }
            }
            $json = json_encode($table_by_hour, JSON_UNESCAPED_UNICODE);
            return [true, $table_by_hour, $json, 24];
        }

    }
    else
    {
        return (false);
    }
}
from_db_to_json($_GET["calendar"], $_GET["is_hours"]);
echo from_db_to_json($_GET["calendar"], $_GET["is_hours"]) [2];

