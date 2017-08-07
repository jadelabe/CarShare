$( document ).ready(function() {
(sessionStorage.name!=null){
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
function signUp(){
  var signUpForm = document.getElementById("signUp");
  var form = new FormData(signUpForm);
  $.ajax({
      url         :   "signup.php",
      type        :   "post",
      cache       :   false,
      processData :   false,
      contentType :   false,
      data        :   form,
      success     :   function(result){
      }
    });
}

function sendSMS(){
  $.ajax({
      url       :  "sendSMS.php",
      type      :  "post",
      dataType  :  'json',
      data      :  {phone: document.getElementById('phoneVerify').value},
      success   :  function(result){
        document.getElementById("codeVerify").value = result.code;
      }
  });
  sessionStorage.setItem("phone", document.getElementById('phoneVerify').value);
}

function verifySMS(){
  $.ajax({
      url       :  "verifySMS.php",
      type      :  "post",
      dataType  :  'json',
      data      :  {phone: sessionStorage.getItem("phone"), code: document.getElementById('codeVerify').value},
      success   :  function(result){
        sessionStorage.removeItem("phone");
      }
  });
}
