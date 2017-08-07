<?php
ini_set('display_errors', '1');
ini_set('error_reporting', E_ALL);

//Upload Photo
$target_dir = "img/";
$target_file = $target_dir . basename($_FILES["image"]["name"]);
$uploadOk = 1;
$imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);
// Check if image file is a actual image or fake image
$check = getimagesize($_FILES["image"]["tmp_name"]);
if($check !== false) {
  echo "File is an image - " . $check["mime"] . ".<br>";
  $uploadOk = 1;
} else {
  echo "File is not an image.<br>";
  $uploadOk = 0;
}
// Check if file already exists
if (file_exists($target_file)) {
  echo "Sorry, file already exists.<br>";
  $uploadOk = 0;
}
// Check file size
if ($_FILES["image"]["size"] > 500000) {
 echo "Sorry, your file is too large.<br>";
 $uploadOk = 0;
}
// Allow certain file formats
if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg") {
  echo "Sorry, only JPG, JPEG & PNG files are allowed.<br>";
  $uploadOk = 0;
}
// Check if $uploadOk is set to 0 by an error
if ($uploadOk == 0) {
  echo "Sorry, your file was not uploaded.<br>";
// if everything is ok, try to upload file
} else {
  $filename = 'img/'. uniqid();
  if (move_uploaded_file($_FILES["image"]["tmp_name"], $filename)) {
    echo "The file ". basename( $_FILES["image"]["name"]). " has been uploaded.<br>";
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
    $dni = mysqli_real_escape_string($conn, $_POST['DNIRegister']);
    $name = mysqli_real_escape_string($conn, $_REQUEST['nameRegister']);
    $email = mysqli_real_escape_string($conn, $_REQUEST['emailRegister']);
    $phoneNumber = mysqli_real_escape_string($conn, $_REQUEST['MobilePhoneNumberRegister']);
    $password = mysqli_real_escape_string($conn, $_REQUEST['PasswordRegister']);
    $driver = mysqli_real_escape_string($conn, $_REQUEST['driver']);

    $sql = "INSERT INTO users (dni, name, email, phoneNumber, password, pathToImage, driver)
            VALUES ('$dni', '$name','$email', '$phoneNumber', '$password','$filename', '$driver')";
    if ($conn->query($sql) === TRUE) {
      echo "New record created successfully<br>";
    } else {
      echo "Error: " . $sql . "<br>" . $conn->error;
    }
    $conn->close();
  } else {
    echo "Sorry, there was an error uploading your file.<br>";
  }
}
?>
