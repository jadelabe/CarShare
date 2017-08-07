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
$passengerID = mysqli_real_escape_string($conn, $_POST['dni']);
$rate = mysqli_real_escape_string($conn, $_POST['rate']);
$comment = mysqli_real_escape_string($conn, $_POST['comment']);

$sql = "UPDATE passengers SET tripRating = '$rate', comment = '$comment'
 WHERE journeyID = '$journeyID' AND passengerID ='$passengerID';";
if ($conn->query($sql) === TRUE) {
  echo "Rating added successfully<br>";
} else {
  echo "Error: " . $sql . "<br>" . $conn->error;
}
//Gettin Driver DNI for updatir overrall rating
$sql = "SELECT dniDriver FROM journey WHERE id ='$journeyID';";
$driverid = $conn->query($sql);
$row = $driverid->fetch_assoc();
$dniDriver = $row['dniDriver'];

$sql = $conn->query("SELECT tripRating FROM passengers WHERE journeyID = '$journeyID';");
$numOfRatings = -1;
$totalRating = 0;
while ($data = mysqli_fetch_assoc($sql)){
     $numOfRatings++;
     $totalRating = $totalRating + $data['tripRating'];
}
$rating = $totalRating/$numOfRatings;

$sql = "UPDATE users SET rating = '$rating' WHERE dni = '$dniDriver';";
if ($conn->query($sql) === TRUE) {
  echo "Driver updated successfully<br>";
} else {
  echo "Error: " . $sql . "<br>" . $conn->error;
}

$conn->close();
?>
