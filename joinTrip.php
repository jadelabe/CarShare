<?php
ini_set('display_errors', '1');
ini_set('error_reporting', E_ALL);
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
$passengerID = mysqli_real_escape_string($conn, $_POST['dni']);
$originID = mysqli_real_escape_string($conn, $_POST['originID']);
$destinationID = mysqli_real_escape_string($conn, $_POST['destinationID']);
$numOfPackages = mysqli_real_escape_string($conn, $_POST['numOfPackages']);
$tripPrice = mysqli_real_escape_string($conn, $_POST['tripPrice']);

$sql = "INSERT INTO passengers (journeyID, passengerID, originStopID, destinationStopID, numOfPackages, tripPrice)
VALUES ('$journeyID','$passengerID', '$originID', '$destinationID', '$numOfPackages', '$tripPrice');";
if ($conn->query($sql) === TRUE) {
  echo ": Passenger created successfully<br>";
} else {
  echo ": Error: " . $sql . "<br>" . $conn->error;
}
//Update all stops
for ($i = 0; $i < $destinationID-$originID; ++$i) {
  $stopID= $originID + $i;
  $sql = "UPDATE stops SET maxPassengers = maxPassengers-1, maxPackages = maxPackages -'$numOfPackages' WHERE stopID = '$stopID';";
  if ($conn->query($sql) === TRUE) {
    echo ": Stop updated successfully<br>";
  } else {
    echo ": Error: " . $sql . "<br>" . $conn->error;
  }
}
$conn->close();
?>
