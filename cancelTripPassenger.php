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

$dni = mysqli_real_escape_string($conn, $_POST['dni']);
$journeyID = mysqli_real_escape_string($conn, $_POST['journeyID']);
$originID = mysqli_real_escape_string($conn, $_POST['originID']);
$destinationID = mysqli_real_escape_string($conn, $_POST['destinationID']);
$numOfPackages = mysqli_real_escape_string($conn, $_POST['numOfPackages']);

$sql = "UPDATE stops SET maxPassengers = maxPassengers+1, maxPackages= maxPackages+'$numOfPackages'
 WHERE journeyID = '$journeyID'
 AND stopID >= '$originID' AND stopID<'$destinationID';";
if ($conn->query($sql) === TRUE) {
  echo "Trip cancelled successfully<br>";
} else {
  echo "Error: " . $sql . "<br>" . $conn->error;
}
$sql = "DELETE FROM passengers
 WHERE journeyID = '$journeyID'
 AND passengerID = '$dni';";
if ($conn->query($sql) === TRUE) {
  echo "Passenger dropped successfully<br>";
} else {
  echo "Error: " . $sql . "<br>" . $conn->error;
}
$conn->close();
?>
