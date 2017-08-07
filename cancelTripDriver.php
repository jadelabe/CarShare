<?php
ini_set('display_errors', '1');
ini_set('error_reporting', E_ALL);
//MariaDB Connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "CarShare";
// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
   die("Connection failed: " . $conn->connect_error) . "<br>";
}

$journeyID = mysqli_real_escape_string($conn, $_POST['journeyID']);

$sql = "UPDATE journey SET canceled = 1 WHERE id = '$journeyID';";
if ($conn->query($sql) === TRUE) {
  echo "Trip cancelled successfully<br>";
} else {
  echo "Error: " . $sql . "<br>" . $conn->error;
}

$conn->close();
?>
