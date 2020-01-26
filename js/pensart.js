"use strict";

// -- Selectors --
var selectedClass = "part-social__item__link";
var dataAttention = document.getElementsByClassName(selectedClass);

// -- Functions --
function askAttention() {

    var random = Math.floor(Math.random() * dataAttention.length);
    dataAttention[random].className += " attention";

    setTimeout(function () {
        dataAttention[random].className = selectedClass;
    }, 3000);
}
// -- Run --
setInterval(askAttention, 5000);

function validateInput(str) {
    var xmlhttp = new XMLHttpRequest();
    var params = 'name=guypensart&email=guypensart@test.be&message=ditiseengeweldigvoorbeeld';
    xmlhttp.open("POST", "handleForm.php", true);
    xmlhttp.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
    xmlhttp.onreadystatechange = function() {
      if (this.readyState == 4 && this.status == 200) {
        var myObj = JSON.parse(this.responseText);
        console.log(myObj);
      }
    };
    xmlhttp.send(params);
  }