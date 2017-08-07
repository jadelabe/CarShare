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
$code = mysqli_real_escape_string($conn, $_POST['code']);
$phone = mysqli_real_escape_string($conn, $_POST['phone']);
$verifyCode = mysqli_fetch_assoc($conn->query("SELECT verifyCode FROM users WHERE phoneNumber = $phone"))["verifyCode"];

if($verifyCode == $code){
  $sql = "UPDATE users SET verified ='1' WHERE phoneNumber = '$phone'";
  if ($conn->query($sql) === TRUE) {
    echo "New record created successfully<br>";
  } else {
    echo "Error: " . $sql . "<br>" . $conn->error;
  }
} else {
  echo "Wrong Code<br>";
}
$conn->close();
?>
