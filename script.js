var password = document.getElementById("password")
  , confirm_password = document.getElementById("confirm_password");

function validatePassword() {
  if (password.value != confirm_password.value) {
    confirm_password.setCustomValidity("Passwords Don't Match");
  } else {
    confirm_password.setCustomValidity('');
  }
}

password.onchange = validatePassword;
confirm_password.onkeyup = validatePassword;

  
let client_radio = document.getElementById('client')
let client_fields = document.getElementById('client_fields')
let client_id_no = document.getElementById('id_no')

function showClientFields() {
  if (client_radio.checked) {
    client_fields.style.display = 'block'
    client_id_no.disabled = false
    client_id_no.required = true
  } else {
    client_fields.style.display = 'none'
    client_id_no.disabled = true
    client_id_no.required = false
  }
}