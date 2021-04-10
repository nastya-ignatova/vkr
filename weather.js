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

function buildCharts(data) {
    anychart.onDocumentLoad(function() {
        var chart = anychart.line(data.map(function(row) {return {x: row[1], value: row[4]}}));
        chart.title("График мощности");
        chart.getSeries(0).name("Мощность");

        // multi-hover enabling
        chart.interactivity("by-x");
        chart.crosshair(true);

        chart.container("container").draw();
        chart.xAxis().title("Время");
        chart.xAxis().labels().format(function() {
            var value = this.value;
            var time = new Date('1970-01-01 ' + value);
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

        var chart = anychart.line(data.map(function(row) {return {x: row[1], value: row[5]}}));
        chart.title("График номинальной мощности");
        chart.getSeries(0).name("Pном");

        // multi-hover enabling
        chart.interactivity("by-x");
        chart.crosshair(true);

        chart.container("container").draw();
        chart.xAxis().title("Время");
        chart.xAxis().labels().format(function() {
            var value = this.value;
            var time = new Date('1970-01-01 ' + value);
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
}

function updateData() {
    let hour_24 = document.getElementById('by_24_hour').checked ? 1 : 0;
    let date = document.getElementById('date').value;
    getJSON(`http://localhost/weather/json.php?calendar=${date}&is_hours=${hour_24}`,
    function(err, data) {
    if (err !== null) {
        alert('Something went wrong: ' + err);
    } else {
        let table = document.getElementById("main_table");
        let tbody = table.childNodes[0];
        while (tbody.childNodes.length !=1){
            tbody.removeChild(tbody.lastChild);
        }
        for (let row of data) {
            var tr = document.createElement('tr');
            tbody.appendChild(tr);
            for (let t of row.slice(1))
            {
                var td = document.createElement('td');
                td.setAttribute('bgcolor', 'lightblue');
                td.setAttribute('align', 'center');
                td.appendChild(document.createTextNode(t));
                tr.appendChild(td);
            }
         }
    }
    });
}
