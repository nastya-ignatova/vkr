<?php
include "config.php";
include "json.php";

if (isset($_POST['date'])&& $_POST['date']!="") {
    $calendar= $_POST['date'];
}
else{                       //начало работы - устанавливается сегодняшняя дата
    $calendar = date("Y-m-d");
    //$foo=true;
}

?>

<head>
    <meta charset="utf-8" />
    <style>
        html, body, #container {
            width: 1200px;
            height: 550px;
            align:center;
            margin: 0;
            padding: 0;
        }

    </style>
    <title>Energy</title>
</head>
<body>
<img src="https://i2.wampi.ru/2020/06/24/igvie.jpg" alt="Альтернативный текст" height="200px" margin-left="20px">
<table align="center">
    <tr>
        <td>
            <form method="POST" action="">

                <p> <center><label for="date">Дата: </label>
                    <input type="date" id="date" name="date" value="<?php echo $calendar;?>" >
                    <button type="submit" name="submit">Ок</button>
                    <p><input name="sorting_of_table" type="radio" value="by_24_hour" checked id="by_24_hour">Таблица на каждый час</p>
                    <p><input name="sorting_of_table" type="radio" value="all_values" id ="all_values"> Все значения</p>
                    
                    <?php
                    $html="";
                    if (($_POST['date']!="" && isset ($_POST['date'])) /*|| $foo==true*/)//если дата установлена
                    {
                        if ($_POST['date']!=""){
                            $calendar=$_POST['date'];//введенная дата  
                            
                           if (from_db_to_json($calendar)[0]=== true){//если данный день есть в БД
                            $list = from_db_to_json($calendar)[1];// построчно вытаскиваем значения
                            $rows_count =from_db_to_json($calendar)[3];
                            //------------------------------------------ТАБЛИЦА ИЗ 24 ЗНАЧЕНИЙ
                                                      
                                $table_by_hour = array();
                                $table_by_hour[0]=$list[0];
                                    for ($i=1;$i<count($list);$i++){
                                        if (Date("H", mktime(end($table_by_hour)[1]))!=Date("H", mktime($list[$i][1])))
                                            {
                                                $table_by_hour[] =$list[$i];
                                            }
                                        }
                                    $html=$html. "<b><i><h2><center style=\"margin-top: 550px;\"><font  face=\"Book antiqua\">Замеры на " . date("d.m.Y", strtotime($calendar)) . ":</font></b></i></h2></center>";
                                    $html=$html. "<center><table border=0><tr>";
                                    $tbl_header=';Время;Напряжение,В; Ток,А; Мощность,Вт;Pном;L;T';
                                    $dat_arr = explode(";", $tbl_header);

                                        for ($p = 1; $p < count($dat_arr); $p++) {
                                            $html=$html. "<td bgcolor=lightblue><center><b><i>$dat_arr[$p]";
                                        }
                                    $html=$html. "</tr>";
                                        for ($i = 0; $i < 24; $i++) {//цикл по строкам
                                            $html=$html. "<tr>";
                                                for ($f = 1; $f < 8; $f++) {//цикл по столбцам
                                                    $html=$html. "<td bgcolor=lightblue><center><b><i>".$table_by_hour[$i][$f];
                                                }

                                             $html=$html. "</tr>";
                                            }

                                        $html=$html. "</table></center>";
                            
                            /////------------------------------------------ТАБЛИЦА ИЗ ВСЕХ 2883 ЗНАЧЕНИЙ
                            if (isset($_POST['by_24_hour'])){echo "<p>by_24_hour<p>";}
                            if (isset($_POST['all_values'])){echo "<p>all_values<p>";}
                            $data = File($filename);
                            $href='Am_12_'.$calendar.'.csv';
                            $html1=$html1. '<p><a href="'.$href.'"><font  face="Book antiqua">Загрузить данные за эту дату</font></a>';
                            
                            
                            //-----------Для построения графиков
                            $html1=$html1. "<p>Полная таблица значений";
                            $html1=$html1. "<center><table border=0><tr>";
                            $tbl_header=';Время;Напряжение,В; Ток,А; Мощность,Вт;Pном;L;T';
                            $dat_arr = explode(";", $tbl_header);

                            for ($p = 1; $p < count($dat_arr); $p++) {
                                $html1=$html1.  "<td bgcolor=lightblue><center><b><i>$dat_arr[$p]".'</td>';
                            }
                            $html1=$html1. "</tr>";
                            for ($i = 0; $i < $rows_count; $i++) {//цикл по строкам

                                $html1=$html1.  "<tr>";
                                for ($f = 1; $f < 8; $f++) {//цикл по столбцам
                                    $html1=$html1.  "<td bgcolor=lightblue><center><b><i>".$list[$i][$f].'</td>';

                                }

                                $html1=$html1. "</tr>";
                            }

                            $html1=$html1.  "</table></center>";
                        echo $html1;

                            echo ' <div id="container"></div>

        <script>
            var id1 = '.json_encode($list).'//передача массива дат и производительностей из php в javascript
        </script>
        <script src="https://cdn.anychart.com/js/latest/anychart-bundle.min.js"></script>
        <script>

            anychart.onDocumentLoad(function() {

                // create chart and set data
                // as Array of Objects
                // the biggest point is marked with individually conigured marker

                var chart = anychart.line(id1.map(function(row) {return {x: row[1], value: row[4]}}));
                chart.title("График мощности");
                chart.getSeries(0).name("Мощность");

                // multi-hover enabling
                chart.interactivity("by-x");
                chart.crosshair(true);

                chart.container("container").draw();
                chart.xAxis().title("Время");
                chart.xAxis().labels().format(function() {
                    var value = this.value;
                    var time = new Date(\'1970-01-01 \' + value);
                    var hours = time.getHours();
                    if (time.getMinutes() >= 30) {
                        hours++;
                        if (hours >= 24) {
                            hours -= 24;
                        }
                    }
                    return hours + ":00";
                });
                chart.yAxis().title("P, Вт");

            });

            anychart.onDocumentLoad(function() {

                var chart = anychart.line(id1.map(function(row) {return {x: row[1], value: row[5]}}));
                chart.title("График номинальной мощности");
                chart.getSeries(0).name("Pном");

                // multi-hover enabling
                chart.interactivity("by-x");
                chart.crosshair(true);

                chart.container("container").draw();
                chart.xAxis().title("Время");
                chart.xAxis().labels().format(function() {
                    var value = this.value;
                    var time = new Date(\'1970-01-01 \' + value)'.';'.'
                    var hours = time.getHours();
                    if (time.getMinutes() >= 30) {
                        hours++;
                        if (hours >= 24) {
                            hours -= 24;
                        }
                    }
                    return hours + ":00";
                });
                chart.yAxis().title("Pном, Вт");

            });

        </script>';

                            
                       }else {echo '<p><font size="7"  face="Book antiqua">Замеров за эту дату нет</font>';}   
                    }
                                        
                    }
                    else {$html=$html.'<p><font size="7"  face="Book antiqua">Введите дату</font>';}
                    echo $html;
                    ?>
                </center>

            </form>
        </td>
    </tr>
</table>

</body>