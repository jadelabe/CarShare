$( document ).ready(function() {
  $.ajax({
    url: 'getOrigin.php',
    dataType  :  'json',
    success: function(result) {
      result.city.forEach(function(cit){
        $('#origin').append("<option value="+cit+">"+cit+"</option>");
      })
      var city = result.city[0];
      getDestination(city);
    }
  });
  if(sessionStorage.name!=null){
    var logged = document.getElementsByClassName('logged');
    for(var i = 0; i != logged.length; ++i){
      logged[i].style.display = "block";
    }
    var notLogged = document.getElementsByClassName('notLogged');
    for(var i = 0; i != notLogged.length; ++i){
      notLogged[i].style.display = "none";
    }
    document.getElementById('welcome').innerHTML ='<p3>Hello '+ sessionStorage.name +'.</p3><a href="javascript:logout()">LogOut</a>';
  }
});

$(document).on("change", "select[id='origin']", function(){
  var city = $( "#origin" ).val();
  $( "#destination" ).empty();
  var city = $( "#origin" ).find( "option:selected" ).text();
  getDestination(city);
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

function getDestination(city){
  $.ajax({
    type: "POST",
    url: "getDestination.php",
    dataType  :  'json',
    data: {city: city},
    success: function(result){
      result.city.forEach(function(cit){
        $('#destination').append(cit);
      })
    }
  });
}
function search(){
  var origin = $( "#origin" ).val();
  var destination = $( "#destination" ).val();
  var date =$( "#date" ).val();
  var rating = $( "#rating" ).val();
  var price =$( "#price" ).val();
  var completedTrips =$( "#completedTrips" ).val();
  for (i= document.getElementById("trips").rows.length; i > 1; i--) {
    document.getElementById("trips").deleteRow(i-1);
  }

  $.ajax({
    type: "POST",
    url: "search.php",
    dataType  :  'json',
    data: {origin: origin, destination: destination, date: date},
    success: function(result){
      if(result.journeyID.length == 0){
        document.getElementById('trips').style.display = "none";
        alert("No trips found");
      } else {
        document.getElementById('trips').style.display = "block";
        for(journeyID in result.journeyID){
          var i=0;
          if((rating == "" || rating <= result.driverRating)&&
          (price == "" || price >= result.price)&&
          (completedTrips =="" || completedTrips <= result.completedTrips)){
            var table = document.getElementById("trips");
            var row = table.insertRow(i+1);
            var cell0 = row.insertCell(0);
            var cell1 = row.insertCell(1);
            var cell2 = row.insertCell(2);
            var cell3 = row.insertCell(3);
            var cell4 = row.insertCell(4);
            var cell5 = row.insertCell(5);
            var cell6 = row.insertCell(6);
            var cell7 = row.insertCell(7);
            var cell8 = row.insertCell(8);
            var cell9 = row.insertCell(9);
            var cell10 = row.insertCell(10);
            var cell11 = row.insertCell(11);
            cell0.innerHTML = origin;
            cell1.innerHTML = destination;
            cell2.innerHTML = result.departureTime;
            cell3.innerHTML = result.arrivalTime;
            cell4.innerHTML = result.maxPassengers;
            cell5.innerHTML = result.price;
            cell6.innerHTML = result.maxPackages;
            cell7.innerHTML = result.pricePackage;
            cell8.innerHTML = result.driver;
            cell9.innerHTML = result.driverRating;
            cell10.innerHTML = result.driverEmail;
            cell11.innerHTML = result.driverPhone;
            if(sessionStorage.name!=null && sessionStorage.driver=="0"){
              var cell12 = row.insertCell(12);
              var cell13 = row.insertCell(13);
              cell12.innerHTML = "<input type='number' name='passengerPackages' min='0' max='"+result.maxPackages+"'>";
              cell13.innerHTML ="<button value="+result.journeyID+">Join Trip</button>";
            }
            i++;
          }
        }
      }
    }});
  }
