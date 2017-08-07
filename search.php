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


$origin = mysqli_real_escape_string($conn, $_POST['origin']);
$destination = mysqli_real_escape_string($conn, $_POST['destination']);
$date = mysqli_real_escape_string($conn, $_POST['date']);

$sql = $conn->query("SELECT journeyID, stopID, departureTime, maxPassengers, price, maxPackages, pricePackages
  FROM stops
  WHERE (journeyID = (SELECT journeyID FROM stops WHERE city = '$origin'))
  AND (journeyID = (SELECT journeyID FROM stops WHERE city = '$destination'))
  AND city = '$origin'
  AND date(DepartureTime) = '$date'
  ORDER BY journeyID;");

  $obj = new \stdClass();
  $obj->journeyID = array();
  $obj->originID = array();
  $obj->destinationID = array();
  $obj->departureTime = array();
  $obj->arrivalTime = array();
  $obj->maxPassengers = array();
  $obj->price = array();
  $obj->maxPackages = array();
  $obj->pricePackage = array();
  $obj->driver = array();
  $obj->driverRating = array();
  $obj->driverEmail = array();
  $obj->driverPhone = array();
  $obj->completedTrips = array();
  $obj->canceled = array();
  $i = 0;

while ($data = mysqli_fetch_assoc($sql)) {
  $id =  $data['journeyID'];
  $obj->journeyID[$i] = $data['journeyID'];
  $obj->originID[$i] = $data['stopID'];
  $obj->departureTime[$i] = $data['departureTime'];
  $obj->maxPassengers[$i] = $data['maxPassengers'];
  $originPrice = $data['price'];
  $obj->maxPackages[$i] = $data['maxPackages'];
  $oriPackPrice = $data['pricePackages'];
  $sql1 = $conn->query("SELECT stopID, arrivalTime, price, pricePackages FROM stops
    WHERE journeyID =   '$id'
    AND city = '$destination'
    ORDER BY journeyID;");
  $data1 = mysqli_fetch_assoc($sql1);
  $obj->arrivalTime[$i] = $data1['arrivalTime'];
  $obj->destinationID[$i] = $data1['stopID'];
  $desPackPrice = $data1['pricePackages'];
  //Setting correct price for the stop and packages
  $arrivalPrice = $data1['price'];
  $obj->price[$i] = $originPrice - $arrivalPrice;
  $obj->pricePackage[$i] =$oriPackPrice - $desPackPrice;

  $sql1 = $conn->query("SELECT dniDriver FROM journey
    WHERE id =   '$id';");
  $dniDriver =mysqli_fetch_assoc($sql1)['dniDriver'];

  $sql1 = $conn->query("SELECT name, rating, email, phoneNumber, finishedDriverTrips FROM users
    WHERE dni =   '$dniDriver';");
  $data1 = mysqli_fetch_assoc($sql1);
  $obj->driver[$i] = $data1['name'];
  $obj->driverRating[$i] = $data1['rating'];
  $obj->driverEmail[$i] = $data1['email'];
  $obj->driverPhone[$i] = $data1['phoneNumber'];
  $obj->completedTrips[$i] = $data1['finishedDriverTrips'];

  $sql1 = $conn->query("SELECT canceled FROM journey WHERE id = '$id';");
  $obj->canceled[$i] = mysqli_fetch_assoc($sql1)['canceled'];
  $i++;
}
$JSON = json_encode($obj);
echo $JSON;
$conn->close();
?>
