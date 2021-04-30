<?php
function load_from_db($calendar,$calendar2, $sampling_step)
{
    include "config.php"; //подключаем БД
    if ($sampling_step === 'all_values')
        $query = "SELECT *  FROM date_table WHERE date_field BETWEEN '".$calendar."' AND '".$calendar2."'";
    else if ($sampling_step === 'by_1_hour')
        $query = "SELECT *  FROM date_table WHERE date_field BETWEEN '".$calendar."' AND '".$calendar2."'  GROUP BY date_field, HOUR(time_field)";
    else if ($sampling_step === 'by_2_hour')
        $query = "SELECT *  FROM date_table WHERE date_field BETWEEN '".$calendar."' AND '".$calendar2."' GROUP BY date_field, HOUR(time_field) div 2 ";
    else if ($sampling_step === 'by_12_hour')
        $query = "SELECT *  FROM date_table WHERE date_field BETWEEN '".$calendar."' AND '".$calendar2."'  GROUP BY date_field, HOUR(time_field) div 12";
    else if ($sampling_step === 'daily_average')
        $query = "SELECT date_field, time_field, AVG(wind_speed) as wind_speed, AVG(wind_direction) as wind_direction, AVG(pressure) as pressure, AVG(t) as t, AVG(humidity) as humidity  FROM date_table WHERE date_field  BETWEEN '".$calendar."' AND '".$calendar2."'  GROUP BY date_field, HOUR(time_field) div 24";
    else 
    {
        return null;
    }
    $result = mysqli_query($conn, $query) or die(mysqli_error($conn));
    if (mysqli_num_rows($result) > 0)
    { //если файл существует на сервере
        $rows_count = mysqli_num_rows($result);
        $i = 0;
        while ($row = mysqli_fetch_array($result))
        { //создаем массив list  сполями как в БД и присваиваем ему соответсвующие значения
            $list[$i][0] = $row['date_field'];
            $list[$i][1] = $row['time_field'];
            $list[$i][2] = $row['wind_speed'];
            $list[$i][3] = $row['wind_direction'];
            $list[$i][4] = $row['pressure'];
            $list[$i][5] = $row['t'];
            $list[$i][6] = $row['humidity'];
            $i++;
        }

        return $list;
    }
    else
    {
        return null;
    }
}
