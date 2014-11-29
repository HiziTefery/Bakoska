<?php
$servername = "localhost";
$username = "pi_user";
$password = "arthas4259";
$dbname = "rpi_db";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} 

//include db.php;

$sql = "SELECT id, temperature, barometric_pressure, humidity FROM measuredData";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    echo "
	<div id='data'><table id=data_table><tr><th>ID</th><th>temperature</th><th>barometric pressure</th><th>humidity</th></tr>";
    // output data of each row
    while($row = $result->fetch_assoc()) {
        echo "<tr><td>".$row["id"]."</td><td>".$row["temperature"]."</td><td>".$row["barometric_pressure"]."</td><td>".$row["humidity"]."</td></tr>";
    }
    echo "</table></div>";
} else {
    echo "0 results";
}
$conn->close();
?>

