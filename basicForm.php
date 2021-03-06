<?php
include "sessionControl.php";
try 
{
    // database connection
    try{
	$conn = new PDO("mysql:host=$dbhost;dbname=$dbname",$dbuser,$dbpass);
	$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch(PDOException $e) {
	echo 'ERROR: ' . $e->getMessage();
    }
    $data = $conn->query("SELECT * FROM measuredData ORDER BY timestamp_of_measurement DESC");
    $rowsFound = $data->rowCount();
    if($rowsFound > 0) {
	echo "
	    <div class='table-container'><div id='overfl'><table id=data_table><tr><th>ID</th><th>temperature</th><th>barometric pressure</th><th>humidity</th><th>timestamp of measurement</th></tr>";
	// output data of each row
    foreach($data as $row) 
    {	
	    echo "<tr><td>".$row["id"]."</td><td>".$row["temperature"]."</td><td>".$row["barometric_pressure"]."</td><td>".$row["humidity"]."</td><td>".$row["timestamp_of_measurement"]."</td></tr>";
	}
	echo "</table></div></div>";

    }
    else {
	echo "DB configuration table is empty!";
    }
    $conn = null;
}
catch(PDOException $e) 
{
    echo $e->getMessage();
}
?>

