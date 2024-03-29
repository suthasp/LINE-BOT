<!--
  Rui Santos
  Complete project details at https://RandomNerdTutorials.com
  
  Permission is hereby granted, free of charge, to any person obtaining a copy
  of this software and associated documentation files.
  
  The above copyright notice and this permission notice shall be included in all
  copies or substantial portions of the Software.

-->
<?php

$servername = "localhost";

// REPLACE with your Database name
$dbname = "touchcom_esp32";
// REPLACE with Database user
$username = "touchcom_board";
// REPLACE with Database user password
$password = "QngSDWimanyz2323";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} 

$sql = "SELECT id, value1, value2, value3, reading_time FROM SensorData order by reading_time desc limit 100";

$result = $conn->query($sql);

while ($data = $result->fetch_assoc()){
    $sensor_data[] = $data;
}

$readings_time = array_column($sensor_data, 'reading_time');

// ******* Uncomment to convert readings time array to your timezone ********
/*$i = 0;
foreach ($readings_time as $reading){
    // Uncomment to set timezone to - 1 hour (you can change 1 to any number)
    $readings_time[$i] = date("Y-m-d H:i:s", strtotime("$reading - 1 hours"));
    // Uncomment to set timezone to + 4 hours (you can change 4 to any number)
    //$readings_time[$i] = date("Y-m-d H:i:s", strtotime("$reading + 4 hours"));
    $i += 1;
}*/

$value1 = json_encode(array_reverse(array_column($sensor_data, 'value1')), JSON_NUMERIC_CHECK);
$value2 = json_encode(array_reverse(array_column($sensor_data, 'value2')), JSON_NUMERIC_CHECK);
$value3 = json_encode(array_reverse(array_column($sensor_data, 'value3')), JSON_NUMERIC_CHECK);
$reading_time = json_encode(array_reverse($readings_time), JSON_NUMERIC_CHECK);

/*echo $value1;
echo $value2;
echo $value3;
echo $reading_time;*/

$result->free();
$conn->close();
?>

<!DOCTYPE html>
<html>
<meta name="viewport" content="width=device-width, initial-scale=1">
  <script src="https://code.highcharts.com/highcharts.js"></script>
  <style>
    body {
      min-width: 310px;
    	max-width: 1280px;
    	height: 500px;
      margin: 0 auto;
    }
    h2 {
      font-family: Arial;
      font-size: 2.5rem;
      text-align: center;
    }
  </style>
  <body>
    <h2><span style="color:red">CN-PBI </span>Data Center</h2>
    <div id="chart-temperature" class="container"></div>
    <div id="chart-humidity" class="container"></div>
    <div id="chart-pressure" class="container"></div>
<script>

var value1 = <?php echo $value1; ?>;
var value2 = <?php echo $value2; ?>;
var value3 = <?php echo $value3; ?>;
var reading_time = <?php echo $reading_time; ?>;

var chartT = new Highcharts.Chart({
  chart:{ renderTo : 'chart-temperature' },
  title: { text: 'Temperature' },
  series: [{
    showInLegend: true,
    name: 'BF1,BF2',
    data: value1
  }/* , {
    showInLegend: false,
    name: 'Cisco',
    data: value2
  } */],
  plotOptions: {
    line: { animation: false,
      dataLabels: { enabled: true }
    },
     series: { color: '#f55d10' }
  },
  xAxis: { 
    type: 'datetime',
    categories: reading_time
  },
  yAxis: {
    title: { text: 'Temperature (Celsius)',
    style: {
                color: '#f55d10'

            }
    }   
    //title: { text: 'Temperature (Fahrenheit)' }
  },
  legend: {
        layout: 'vertical',
        align: 'right',
        verticalAlign: 'middle'
    },
  credits: { enabled: false }
});

var chartH = new Highcharts.Chart({
  chart:{ renderTo:'chart-humidity' },
  title: { text: 'Humidity' },
  series: [{
    showInLegend: true,
    name: 'BF1,BF2',
    data: value2
  }],
  plotOptions: {
    line: { animation: false,
      dataLabels: { enabled: true }
    },
    series: { color: '#7cb5ec' }
  },
  xAxis: {
    type: 'datetime',
    //dateTimeLabelFormats: { second: '%H:%M:%S' },
    categories: reading_time
  },
  yAxis: {
    title: { text: 'Humidity (%RH)' ,
      style: {
                color: '#7cb5ec'

            }
    }
  },
  legend: {
        layout: 'vertical',
        align: 'right',
        verticalAlign: 'middle'
    },
  credits: { enabled: false }
});


var chartP = new Highcharts.Chart({
  chart:{ renderTo:'chart-pressure' },
  title: { text: 'Pressure' },
  series: [{
    showInLegend: true,
    name: 'BF1,BF2',
    data: value3
  }],
  plotOptions: {
    line: { animation: false,
      dataLabels: { enabled: true }
    },
    series: { color: '#207905' }
  },
  xAxis: {
    type: 'datetime',
    categories: reading_time
  },
  yAxis: {
    title: { text: 'Pressure (hPa)' ,
      style: {
                color: '#207905'

            }
    }
  },

  legend: {
        layout: 'vertical',
        align: 'right',
        verticalAlign: 'middle'
    },

  credits: { enabled: false }
});

</script>
</body>
</html>
