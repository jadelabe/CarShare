var i = 0;
$( document ).ready(function() {
  if(sessionStorage.name!=null){
    var logged = document.getElementsByClassName('logged');
    for(var i = 0; i != logged.length; ++i){
      logged[i].style.display = "block";
    }
    var notLogged = document.getElementsByClassName('notLogged');
    for(var i = 0; i != notLogged.length; ++i){
      notLogged[i].style.display = "none";
    }
    if(sessionStorage.driver == "0"){
      var passenger = document.getElementsByClassName('passenger');
      for(var i = 0; i != passenger.length; ++i){
        passenger[i].style.display = "table-cell";
      }
    }
    if(sessionStorage.driver == "1"){
      var driver = document.getElementsByClassName('driver');
      for(var i = 0; i != driver.length; ++i){
        driver[i].style.display = "table";
      }
    }
    document.getElementById('welcome').innerHTML ='<p3>Hello '+ sessionStorage.name +'.</p3><a href="javascript:logout()">LogOut</a>';
  } else{
    window.location = "index.html";
  }
  populateTrips()
});
$(document).on('click',"button", function(){
  var form = false;
  form  = document.forms[0];
  setCheckbox(form);
  setRow(form);
  if(checkDestination(form) == false){
    var new_row = $("#new-row-model tbody").clone();
    $("#stops tbody").append(new_row.html());
  } else {
    document.getElementById("registerTrip").style.visibility = "visible";
  }
  document.getElementById("stops").deleteRow(i+1);
  i++;
});
function login(){
  $.ajax({
    url       :  "login.php",
    type      :  "post",
    dataType  :  'json',
    data      :  {dni: document.getElementById('dni').value, pass: document.getElementById('pass').value},
    success   :  function(result){
      sessionStorage.setItem("name", result.name);
      sessionStorage.setItem("dni", result.dni);
      sessionStorage.setItem("driver", result.driver);
      location.reload();
    }
  });
}
function logout(){
  sessionStorage.clear()
  location.reload();
}
function populateTrips(){
  $.ajax({
    url       :  "populateTrips.php",
    type      :  "post",
    dataType  :  'json',
    data      :  {dni: sessionStorage.dni},
    success   :  function(result){
      if(result.journeyID.length == 0){
        document.getElementById('noTrips').innerHTML ='You dont have any trip yet, join one in the home page';
      } else {
        document.getElementById("trips").style.display = "table";
        for(journeyID in result.journeyID){
          var i=0;
          var table = document.getElementById("trips");
          var row = table.insertRow(i+1);
          var cell0 = row.insertCell(0);
          var cell1 = row.insertCell(1);
          var cell2 = row.insertCell(2);
          var cell3 = row.insertCell(3);
          var cell4 = row.insertCell(4);
          var cell5 = row.insertCell(5);
          var cell6 = row.insertCell(6);
          cell0.innerHTML = result.origin;
          cell0.value = result.originID;
          cell1.innerHTML = result.destination;
          cell1.value = result.destinationID;
          cell2.innerHTML = result.departureTime;
          cell2.value = result.journeyID;
          cell3.innerHTML = result.arrivalTime;
          cell4.innerHTML = result.canceled;

          var passengers = "";
          result.passengers.forEach(function(element) {
            passengers = passengers + element + ",<br>";
          });
          if(sessionStorage.driver=="0"){


            var cell7 = row.insertCell(7);
            var cell8 = row.insertCell(8);
            var cell9 = row.insertCell(9);
            var cell10 = row.insertCell(10);
            var cell11 = row.insertCell(11);
            var cell12 = row.insertCell(12);
            var cell13 = row.insertCell(13);
            var cell14 = row.insertCell(14);
            var cell15 = row.insertCell(15);
            var cell16 = row.insertCell(16);
            cell5.innerHTML = result.price;
            cell6.innerHTML = result.myPackages;
            cell7.innerHTML = result.pricePackage;
            cell8.innerHTML = result.totalPrice;
            cell9.innerHTML = result.driver;
            cell10.innerHTML = result.driverEmail;
            cell11.innerHTML = result.driverPhone;
            cell12.innerHTML = passengers;
            cell13.innerHTML = "<input type='number' name='Rate' value='0' min='0' max='5'>";
            cell14.innerHTML = "<input type='text' placeholder='Your opinion...'>";
            cell15.innerHTML = "<form action='javascript:rate("+i+")'><input type='submit' value='Rate'></form>";
            cell16.innerHTML = "<form action='javascript:cancelPassenger("+i+")'><input type='submit' value='Cancel'></form>";
          }
          else{
            cell5.innerHTML = passengers;
            cell6.innerHTML = "<form action='javascript:cancelDriver("+i+")'><input type='submit' value='Cancel'></form>";
          }
          i++;
        }
      }
    }
  });
}
function rate(i){
  i++;
  var table = document.getElementById("trips");
  var journeyID = table.rows[i].cells[2].value[0];
  var rate = table.rows[i].cells[12].children[0].value;
  var comment = table.rows[i].cells[13].children[0].value;
  $.ajax({
    url       :  "rate.php",
    type      :  "post",
    dataType  :  'json',
    data      :  {dni: sessionStorage.dni, rate: rate, comment: comment, journeyID: journeyID},
    success   :  function(result){

    }
  });
}
function cancelPassenger(i){
  i++;
  var table = document.getElementById("trips");
  var journeyID = table.rows[i].cells[2].value[0];
  var originID = table.rows[i].cells[0].value[0];
  var destinationID = table.rows[i].cells[1].value[0];
  var numOfPackages = table.rows[i].cells[6].innerText;
  $.ajax({
    url       :  "cancelTripPassenger.php",
    type      :  "post",
    dataType  :  'json',
    data      :  {dni: sessionStorage.dni, numOfPackages: numOfPackages, 
      journeyID: journeyID, originID: originID, destinationID: destinationID},
    success   :  function(result){

    }
  });
}
function cancelDriver(i){
  i++;
  var table = document.getElementById("trips");
  var journeyID = table.rows[i].cells[2].value[0];
  $.ajax({
    url       :  "cancelTripDriver.php",
    type      :  "post",
    dataType  :  'json',
    data      :  {journeyID: journeyID},
    success   :  function(result){

    }
  });
}
function setCheckbox(form){
  if (form.Destination.checked == true){
    form.Destination.value = "Yes";
  } else {
    form.Destination.value = "No";
  }
  if (form.allowedPackages.checked == true){
    form.allowedPackages.value = "Yes";
  } else {
    form.allowedPackages.value = "No";
  }
}
function setRow(form){
  var table = document.getElementById("stops");
  var row = table.insertRow(i+2);
  var cell0 = row.insertCell(0);
  var cell1 = row.insertCell(1);
  var cell2 = row.insertCell(2);
  var cell3 = row.insertCell(3);
  var cell4 = row.insertCell(4);
  var cell5 = row.insertCell(5);
  var cell6 = row.insertCell(6);
  var cell7 = row.insertCell(7);
  var cell8 = row.insertCell(8);

  cell0.innerHTML = form.Stop.value;
  cell1.innerHTML = form.Destination.value;
  cell2.innerHTML = form.arrivalDate.value;
  cell3.innerHTML = form.departureDate.value;
  cell4.innerHTML = form.maxPassengers.value;
  cell5.innerHTML = form.price.value;
  cell6.innerHTML = form.allowedPackages.value;
  cell7.innerHTML = form.packagePrice.value;
  cell8.innerHTML = form.nPackages.value;
}

function checkDestination(form){
  if (form.Destination.value == "Yes"){
    return true;
  } else {
    return false;
  }
}

function createTrip(){
  var TableData = JSON.stringify(tableToArray());
  var driverDNI = JSON.stringify(sessionStorage.dni);
  $.ajax({
    type: "POST",
    url: "createTrip.php",
    data: {TableData: TableData, dniDriver:driverDNI},
    success: function(msg){
      alert("Trip successfully created");
    }
  });
}
function tableToArray(){
  var TableData = new Array();
  $('#stops tr').each(function(row, tr){
    TableData[row]={
      "stop" : $(tr).find('td:eq(0)').text()
      , "isDestination" :$(tr).find('td:eq(1)').text()
      , "arrivalDate" : $(tr).find('td:eq(2)').text()
      , "departureDate" : $(tr).find('td:eq(3)').text()
      , "maxPassengers" : $(tr).find('td:eq(4)').text()
      , "price" :$(tr).find('td:eq(5)').text()
      , "allowedPackages" : $(tr).find('td:eq(6)').text()
      , "packagePrice" : $(tr).find('td:eq(7)').text()
      , "nPackages" : $(tr).find('td:eq(8)').text()
    }
  });
  TableData.shift();
  return TableData;
}
