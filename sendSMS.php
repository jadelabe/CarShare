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
$phoneNumber = mysqli_real_escape_string($conn, $_POST['phone']);

$result = $conn->query("SELECT phoneNumber FROM users WHERE phoneNumber = '$phoneNumber'");
if($result->num_rows == 0) {
  echo "Phone number not registered, please, sign up before trying to verify your phone<br>";
} else {
  //Creating random 5 digit number
  $code=rand(pow(10, 5-1), pow(10, 5)-1);

/* API Post petition
 $url = 'http://localhost/api/sms';
  $data = array('login' => 'prueba',
                'password' => 'prueba',
                'telefono' => $phoneNumber,
                'codigo' => $code);

  // use key 'http' even if you send the request to https://...
  $options = array(
    'http' => array(
        'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
        'method'  => 'POST',
        'content' => http_build_query($data)
    )
  );
  $context  = stream_context_create($options);
    $result = file_get_contents($url, false, $context);
  if ($result === FALSE) { echo "API Error"; }

  var_dump($result);
  */
  $obj = new \stdClass();
  $obj->code = $code;
  $JSON = json_encode($obj);
  #Adding verify code to the DB for further use
  $sql = "UPDATE users SET verifyCode ='$code' WHERE phoneNumber = '$phoneNumber'";
  if ($conn->query($sql) === TRUE) {
     echo "Verify code added successfully<br>";
  } else {
      echo "Error: " . $sql . "<br>" . $conn->error;
  }

  echo $JSON;
}
$conn->close();
?>
