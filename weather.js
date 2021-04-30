var getJSON = function(url, callback) {
    var xhr = new XMLHttpRequest();
    xhr.open('GET', url, true);
    xhr.responseType = 'json';
    xhr.onload = function() {
      var status = xhr.status;
      if (status === 200) {
        callback(null, xhr.response);
      } else {
        callback(status, xhr.response);
      }
    };
    xhr.send();
};

function buildChart(data,y_index,y_title, chart_title, series_title) {
    var chart = anychart.line(data.map(function(row) {return {x: row[0]+' ' +row[1], value: row[y_index]}}));
    chart.title(chart_title);
    chart.getSeries(0).name(series_title);

    // multi-hover enabling
    chart.interactivity("by-x");
    chart.crosshair(true);
    chart.palette(["Black"]);
    chart.container("container").draw();
    chart.xAxis().title("Время");
    chart.xAxis().labels().format(function() {
        var value = this.value;          
        return value;
       
    });
    chart.yAxis().title(y_title);

}

function buildChart_wind_direction(data,y_index,y_title, chart_title, series_title) {
    /*var chart = anychart.line(data.map(function(row) {return {x: row[0]+' ' +row[1], value: row[y_index]}}));
    chart.title(chart_title);
    chart.getSeries(0).name(series_title);

    // multi-hover enabling
    chart.interactivity("by-x");
    chart.crosshair(true);
    chart.palette(["Black"]);
    chart.container("container").draw();
    chart.xAxis().title("Время");
    chart.xAxis().labels().format(function() {
        var value = this.value;          
        return value;
       
    });
    chart.yAxis().title(y_title);*/
        chart.getSeries(0).name(series_title);
    var data_1 = [
        {x: "Север", value: 0},
        {x: "Северо-восток", value: 0},      
        {x: "Восток", value: 0},
        {x: "Юго-восток", value: 0},
        {x: "Юг", value: 0},
        {x: "Юго-запад", value: 0},
        {x: "Запад", value: 0},
        {x: "Северо-запад", value: 0}
      ];

let sever=0;
let severo_vostok=0;
let vostok=0;
let ugo_vostok=0;
let ug=0;
let ugo_zapad=0;
let zapad=0;
let severo_zapad=0;
      for (let i = 0; i < data.length; i++) { // выведет 0, затем 1, затем 2
        if (data[i][y_index]>=337.5 || data[i][y_index]<22.5){sever++;}
        if (data[i][y_index]>=22.5 && data[i][y_index]<67.5){severo_vostok++;}
        if (data[i][y_index]>=67.5 && data[i][y_index]<112.5){vostok++;}
        if (data[i][y_index]>=112.5 && data[i][y_index]<157.5){ugo_vostok++;}
        if (data[i][y_index]>=157.5 && data[i][y_index]<202.5){ug++;}
        if (data[i][y_index]>=202.5 && data[i][y_index]<247.5){ugo_zapad++;}
        if (data[i][y_index]>=247.5 && data[i][y_index]<292.5){zapad++;}
        if (data[i][y_index]>=292.5 && data[i][y_index]<337.5){severo_zapad++;}
      }

      data_1.push({x: "Север", value: sever});
      data_1.push({x: "Северо-восток", value: severo_vostok});
      data_1.push({x: "Восток", value: vostok});
      data_1.push({x: "Юго-восток", value: ugo_vostok});
      data_1.push({x: "Юг", value: ug});
      data_1.push({x: "Юго-запад", value: ugo_zapad});
      data_1.push({x: "Запад", value: zapad});
      data_1.push({x: "Северо-запад", value: severo_zapad});

      // create a chart
      chart = anychart.radar();
      
      // create the first series (line) and set the data
      var series1 = chart.line(data_1);
      
      // set the container id
      chart.container("container");
      
      // initiate drawing the chart
      chart.draw();
}

function buildCharts(data) {
    let div = document.getElementById("container");
        while (div.childNodes.length !=0)
        {
        div.removeChild(div.lastChild);
    }  
    buildChart(data, 2,"V, м/c","График значений скорости ветра","Скорость ветра");
    buildChart_wind_direction(data, 3,"хз","График направления ветра","Направление ветра");
    buildChart(data, 4,"ГПа","График атмосферного давления","Атм. давление");
  
}

function reload() {
    let sampling_step = 'by_1_hour';
    let selected_index = document.getElementById('sampling_step').selectedIndex;
    if (selected_index==1)
    {
        sampling_step = 'by_2_hour';
    }
    else if (selected_index==2)
    {
        sampling_step = 'by_12_hour';
    }
    else if (selected_index==3)
    {
        sampling_step = 'daily_average';
    }
    else if (selected_index==4)
    {
        sampling_step = 'all_values';
    }
    let date1 = document.getElementById('date1').value;
    let date2 = document.getElementById('date2').value;
    const nextURL = `http://localhost/weather/weather.php?calendar=${date1}&calendar2=${date2}&sampling_step=${sampling_step}`;
    const nextTitle = 'My new page title';
    const nextState = { additionalInformation: 'Updated the URL with JS' };
    window.history.pushState(nextState, nextTitle, nextURL);
    window.history.replaceState(nextState, nextTitle, nextURL);
    getJSON(`http://localhost/weather/json.php?calendar=${date1}&calendar2=${date2}&sampling_step=${sampling_step}`,
        function(err, data) {
            if (err !== null) {
                alert('Something went wrong: ' + err);
            } else {
                updateData(data);
            }
        }
    );
}

function updateData(data) {
    buildTable(data);
    buildCharts(data);
}

function buildTable(data) {
        
    let table = document.getElementById("main_table");

        let tbody = table.childNodes[0];
        while (tbody.childNodes.length !=1){
            tbody.removeChild(tbody.lastChild);
            
    }
    for (let row of data) {
        var tr = document.createElement('tr');
        tbody.appendChild(tr);
        for (let t of row.slice(0,1))
        {
            var td = document.createElement('td');
            td.setAttribute('class', 'font_mpei');
            td.setAttribute('bgcolor', 'ff9494');
            td.setAttribute('align', 'center');
            td.setAttribute('style', 'font-size:15px');
            td.appendChild(document.createTextNode(t));
            tr.appendChild(td);
        }
        for (let t of row.slice(1))
        {
            var td = document.createElement('td');
            td.setAttribute('class', 'font_mpei');
            td.setAttribute('bgcolor', 'fff0f5');
            td.setAttribute('align', 'center');
            td.setAttribute('style', 'font-size:15px');
            td.appendChild(document.createTextNode(t));
            tr.appendChild(td);
        }
     }
}