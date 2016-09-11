// -- Selectors --
var selectedClass = "part-social__item__link";
var dataAttention = document.getElementsByClassName(selectedClass);

// -- Functions --
function askAttention() {

  random = Math.floor(Math.random() * dataAttention.length);
  dataAttention[random].className += " attention";
  
  setTimeout(function() {
    dataAttention[random].className = selectedClass;       
  }, 3000);
  
}
// -- Run --
setInterval(askAttention, 5000);