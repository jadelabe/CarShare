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
        passenger[i].style.display = "inherit";
      }
    }
    if(sessionStorage.driver == "1"){
      var driver = document.getElementsByClassName('driver');
      for(var i = 0; i != driver.length; ++i){
        driver[i].style.display = "inherit";
      }
    }
    document.getElementById('welcome').innerHTML ='<p3>Hello '+ sessionStorage.name +'.</p3><a href="javascript:logout()">LogOut</a>';
  } else{
    window.location = "index.html";
  }
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
