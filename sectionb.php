<?php // sectionb.php

echo <<<_END

<!DOCTYPE html>
<html lang="en-US">
<head>
<script type="text/javascript" src="../js/loader.js"></script>
<script type="text/javascript">
// Load google charts
google.charts.load('current', {'packages':['corechart']});
google.charts.setOnLoadCallback(drawChart);

_END;

echo <<<_END
// Draw the chart and set the chart values
function drawChart() {
  var data = google.visualization.arrayToDataTable([
  ['Category', 'Number of books'], //header
_END;

  require_once 'login.php';
  $conn = new mysqli($hn, $un, $pw, $db);
  if ($conn->connect_error) die($conn->connect_error);

  $query  = "SELECT category,count(*) FROM classics group by category;";
  $result = $conn->query($query);
  if (!$result) die ("Database access failed: " . $conn->error);
  $rows = $result->num_rows;



    for ($j = 0 ; $j < $rows ; ++$j)
    {
    $result->data_seek($j);
    $row = $result->fetch_array(MYSQLI_NUM);

      if($j==($rows-1)){
echo <<<_END

    ['$row[0]', $row[1]]
_END;
      }
      else{
echo <<<_END

    ['$row[0]', $row[1]],
_END;
      }

    }


echo <<<_END
 ]);

  // Optional; add a title and set the width and height of the chart
  var options = {'title':'Category of Books', 'width':550, 'height':400};
   //the chart will show the percentage of each task based on the Hours per day values
  // Display the chart inside the <div> element with id="piechart"
  var chart = new google.visualization.PieChart(document.getElementById('piechart'));
  chart.draw(data, options);
}
</script>
</head>
<body>
<h1>Category of Books</h1>
<div id="piechart"></div>
</body>
</html> 
_END;


  $result->close();
  $conn->close();
  
?>