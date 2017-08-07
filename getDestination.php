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
$city = mysqli_real_escape_string($conn, $_POST['city']);

$sql = $conn->query("SELECT city FROM stops
  WHERE (journeyID = (SELECT journeyID FROM stops WHERE city = '$city'))
  AND (stopID > (SELECT stopID FROM stops WHERE city = '$city'))
  GROUP BY city ORDER BY city;");

  $obj = new \stdClass();
  $obj->city = array();
  $i = 0;
while ($data = mysqli_fetch_assoc($sql)) {
  $obj->city[$i] = '<option value="'.$data['city'].'">'.$data['city'].'</option>';
  $i++;
}
  $JSON = json_encode($obj);
  echo $JSON;
?>
