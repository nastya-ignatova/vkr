let hour_24 = 'by_1_hour';
if (document.getElementById('sorting_of_table').selectedIndex==1)
{hour_24 = 'by_2_hour';
}
else if (document.getElementById('sorting_of_table').selectedIndex==2)
{hour_24 = 'by_12_hour';
}
else if (document.getElementById('sorting_of_table').selectedIndex==3)
{hour_24 = 'daily_average';
}
else if (document.getElementById('sorting_of_table').selectedIndex==4)
{hour_24 = 'all_values';
}
let date1 = document.getElementById('date1').value;
let date2 = document.getElementById('date2').value;
getJSON(`http://localhost/weather/json.php?calendar=${date1}&calendar2=${date2}&is_hours=${"hour_24"}`,
function(err, data) {
if (err !== null) {
    alert('Something went wrong: ' + err);
} 
else{buildCharts(data)}
});