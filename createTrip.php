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

$tableData = stripcslashes($_POST['TableData']);
$tableData = json_decode($tableData,TRUE);
$dniDriver = stripcslashes($_POST['dniDriver']);
$dniDriver = json_decode($dniDriver,TRUE);

$date = mysqli_real_escape_string($conn, $tableData[0]['departureDate']);
$dnidriver = mysqli_real_escape_string($conn, $dniDriver);
$dni = $dniDriver;

//Create Journey
$sql = "SET foreign_key_checks = 0;";
$res = $conn->query($sql);
$sql = "INSERT INTO journey (date, dniDriver)
        VALUES ('$date', '$dni');";
if ($conn->query($sql) === TRUE) {
  $id = $conn->insert_id;
  echo "Journey created successfully<br>";
} else {
  echo "Journey: Error: " . $sql . "<br>" . $conn->error;
}
$sql = "SET foreign_key_checks = 1;";
$res = $conn->query($sql);

//Create each one of the Stops
$stops = array();
foreach($tableData as $stops) {
  createStop($conn, $stops, $id);
}

//Get stopID for journey table
$sql = "SELECT stopID FROM stops WHERE stopID =(SELECT min(stopID) FROM stops) AND journeyID = '$id';";
$destination = $conn->query($sql);
$row = $destination->fetch_assoc();
$originID = $row['stopID'];
$sql = "SELECT stopID FROM stops WHERE stopID =(SELECT max(stopID) FROM stops) AND journeyID = '$id';";
$destination = $conn->query($sql);
$row = $destination->fetch_assoc();
$destinationID = $row['stopID'];
//Update journey table with correct stops ID
$sql = "UPDATE journey SET originID = '$originID', destinationID = '$destinationID' WHERE id = '$id';";
$res = $conn->query($sql);

$sql = "INSERT INTO passengers (journeyID, passengerID, originStopID, destinationStopID)
VALUES ('$id','$dni', '$originID', '$destinationID');";
if ($conn->query($sql) === TRUE) {
  echo ": Passenger created successfully<br>";
} else {
  echo ": Error: " . $sql . "<br>" . $conn->error;
}
$conn->close();

function createStop($conn, $stopData, $journeyID){
  $journeyID = mysqli_real_escape_string($conn, $journeyID);
  $city = mysqli_real_escape_string($conn, $stopData['stop']);
  $arrivalTime = mysqli_real_escape_string($conn, $stopData['arrivalDate']);
  $departureTime = mysqli_real_escape_string($conn, $stopData['departureDate']);
  $maxPassengers = mysqli_real_escape_string($conn, $stopData['maxPassengers']);
  $pricePackages = mysqli_real_escape_string($conn, $stopData['packagePrice']);
  $price = mysqli_real_escape_string($conn, $stopData['price']);

  $sql = "INSERT INTO stops (journeyID, city, arrivalTime, departureTime, maxPassengers, pricePackages, price)
  VALUES ('$journeyID', '$city', '$arrivalTime', '$departureTime', '$maxPassengers', '$pricePackages', '$price');";
  if ($conn->query($sql) === TRUE) {
    $id = $conn->insert_id;
    echo $city.": Stop created successfully<br>";
  } else {
    echo $city.": Error: " . $sql . "<br>" . $conn->error;
  }
}
?>
