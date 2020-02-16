"use strict";

// Polyfills
if (!Element.prototype.matches) {
  Element.prototype.matches = Element.prototype.msMatchesSelector ||
    Element.prototype.webkitMatchesSelector;
}

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

/*!
 * Serialize all form data into a query string
 * (c) 2018 Chris Ferdinandi, MIT License, https://gomakethings.com
 * @param  {Node}   form The form to serialize
 * @return {String}      The serialized form data
 */
var serialize = function (form) {

  // Setup our serialized data
  var serialized = [];

  // Loop through each field in the form
  for (var i = 0; i < form.elements.length; i++) {

    var field = form.elements[i];

    // Don't serialize fields without a name, submits, buttons, file and reset inputs, and disabled fields
    if (!field.name || field.disabled || field.type === 'file' || field.type === 'reset' || field.type === 'submit' || field.type === 'button') continue;

    // If a multi-select, get all selections
    if (field.type === 'select-multiple') {
      for (var n = 0; n < field.options.length; n++) {
        if (!field.options[n].selected) continue;
        serialized.push(encodeURIComponent(field.name) + "=" + encodeURIComponent(field.options[n].value));
      }
    }

    // Convert field data to a query string
    else if ((field.type !== 'checkbox' && field.type !== 'radio') || field.checked) {
      serialized.push(encodeURIComponent(field.name) + "=" + encodeURIComponent(field.value));
    }
  }

  return serialized.join('&');

};

// -- Run --
setInterval(askAttention, 5000);

function submitContact(element) {
  var isSingle = (element) ? true : false;
  var form = document.querySelector('#frmContact');
  var formData = serialize(form) + '&single=' + isSingle;
  
  var xmlhttp = new XMLHttpRequest();
  var params = formData;
  xmlhttp.open("POST", "handleForm.php", true);
  xmlhttp.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
  xmlhttp.onreadystatechange = function () {
    if (this.readyState == 4 && this.status == 200) {
  
      var data = JSON.parse(this.responseText);

      (data.submitted) ? document.querySelector('#msgThanks').style.display = 'block': null;

      var filterErrors = (element) ? [ element.name ] : ["name", "email", "message"];

      for (var index = 0; index < filterErrors.length; ++index) {
        var currentInput = filterErrors[index];
        if (data[currentInput].valid == false) {
          document.getElementById(currentInput + 'Error').style.display = 'inline-block';
          document.getElementById(currentInput + 'Error').textContent = data[currentInput].error;
        } else {
          document.getElementById(currentInput + 'Error').style.display = 'none';
        }

        if (data.showSubmit) {
          document.getElementById('submitContact').removeAttribute('disabled');
        } else {
          document.getElementById('submitContact').setAttribute('disabled', "");
        }
      }
    }
  };
  xmlhttp.send(params);
  
}

document.addEventListener('click', function (event) {

  if (event.target.type == 'submit' || event.target.matches('[href^="#"]')) event.preventDefault();

  if (event.target.matches('#submitContact')) submitContact();

}, false);


document.addEventListener('keyup', function (event) {

  if (event.target.matches('[required]')) submitContact(event.target);

}, false);