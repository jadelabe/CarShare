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

$sql = $conn->query("SELECT journeyID, originStopID, destinationStopID, tripPrice, numOfPackages
  FROM passengers
  WHERE passengerID = '$dni'
  ORDER BY journeyID;");

  $obj = new \stdClass();
  $obj->journeyID = array();
  $obj->originID = array();
  $obj->origin = array();
  $obj->destinationID = array();
  $obj->destination = array();
  $obj->departureTime = array();
  $obj->arrivalTime = array();
  $obj->price = array();
  $obj->myPackages = array();
  $obj->pricePackage = array();
  $obj->totalPrice = array();
  $obj->driver = array();
  $obj->driverEmail = array();
  $obj->driverPhone = array();
  $obj->passengers = array();
  $obj->canceled = array();
  $obj->completed = array();
  $i = 0;
  while ($data = mysqli_fetch_assoc($sql)) {
    $id =  $data['journeyID'];
    $oriID = $data['originStopID'];
    $desID = $data['destinationStopID'];
    $obj->journeyID[$i] = $data['journeyID'];
    $obj->originID[$i] = $data['originStopID'];
    $obj->destinationID[$i] = $data['destinationStopID'];
    $obj->totalPrice[$i] = $data['tripPrice'];
    $obj->myPackages[$i] = $data['numOfPackages'];

    //Journey Data
    $sql1 = $conn->query("SELECT dniDriver, canceled, completed FROM journey
      WHERE id = '$id' ORDER BY id;");
    $data1 = mysqli_fetch_assoc($sql1);
    $driverDNI = $data1['dniDriver'];
    $obj->canceled[$i] = $data1['canceled'];
    $obj->completed[$i] = $data1['completed'];
    //Origin Data
    $sql1 = $conn->query("SELECT city, departureTime, price, pricePackages FROM stops
      WHERE stopID = '$oriID' ORDER BY journeyID;");
    $data1 = mysqli_fetch_assoc($sql1);
    $obj->origin[$i] = $data1['city'];
    $obj->departureTime[$i] = $data1['departureTime'];
    $originPrice = $data1['price'];
    $oriPackPrice = $data1['pricePackages'];
    //Destination Data
    $sql1 = $conn->query("SELECT city, arrivalTime, price, pricePackages FROM stops
      WHERE stopID = '$desID' ORDER BY journeyID;");
    $data1 = mysqli_fetch_assoc($sql1);
    $obj->destination[$i] = $data1['city'];
    $obj->arrivalTime[$i] = $data1['arrivalTime'];
    $destinationPrice = $data1['price'];
    $desPackPrice = $data1['pricePackages'];
    //Fixed Prices
    $obj->price[$i] =$originPrice - $destinationPrice;
    $obj->pricePackage[$i] =$oriPackPrice - $desPackPrice;
    //Driver Data
    $sql1 = $conn->query("SELECT name, email, phoneNumber FROM users
      WHERE dni =   '$driverDNI';");
    $data1 =mysqli_fetch_assoc($sql1);
    $obj->driver = $data1['name'];
    $obj->driverEmail = $data1['email'];
    $obj->driverPhone = $data1['phoneNumber'];
    //Passenger Data
    $obj->passengers[$i] = array();
    $x = 0;
    $sql1 = $conn->query("SELECT passengerID FROM passengers
      WHERE journeyID ='$id' AND NOT passengerID ='$dni';");
    while($data1 =mysqli_fetch_assoc($sql1)){
      $passID = $data1['passengerID'];
      $sql1 = $conn->query("SELECT name FROM users
        WHERE dni =   '$passID';");
      $data1 =mysqli_fetch_assoc($sql1);
      $obj->passengers[$i][$x] = $data1['name'];
      $x++;
    }
    $i++;
  }
  $JSON = json_encode($obj);
  echo $JSON;
$conn->close();
?>
