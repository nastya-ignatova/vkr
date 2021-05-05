<?php
include "utils.php";
function from_db_to_json($calendar,$calendar2, $sampling_step)
{
    $list = load_from_db($calendar,$calendar2, $sampling_step);
    if ($list !== null)
    {
        return json_encode($list, JSON_UNESCAPED_UNICODE);   
    }
    else
    {
        return null;
    }
}
header('Content-type: application/json');
echo from_db_to_json($_GET["calendar"],$_GET["calendar2"], $_GET["sampling_step"]);

