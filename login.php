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
$pass = mysqli_real_escape_string($conn, $_POST['pass']);

//Check Password
$passDB = mysqli_fetch_assoc($conn->query("SELECT password FROM users WHERE dni = '$dni'"))["password"];
if($passDB == $pass) {

  $qry = mysqli_fetch_assoc($conn->query("SELECT name, driver FROM users WHERE dni = '$dni'"));
  $obj = new \stdClass();
  $obj->name = $qry['name'];
  $obj->driver = $qry['driver'];
  $obj->dni = $dni;
  $JSON = json_encode($obj);
  echo $JSON;
} else {
  echo "Wrong user or password<br>";
}
$conn->close();
?>
