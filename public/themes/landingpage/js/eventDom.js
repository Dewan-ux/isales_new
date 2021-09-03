
  window.addEventListener("DOMContentLoaded", function(e) {

    var myForm = document.getElementById("myForm");
    var checkForm = function(e) {
      if(!this.bersedia.checked) {
        alert("Silahkan pilih untuk memiilih");
        this.bersedia.focus();
        e.preventDefault(); // equivalent to return false
        return;
      }
    };

    // attach the form submit handler
    myForm.addEventListener("submit", checkForm, false);

    var myCheckbox = document.getElementById("bersedia");
    var myCheckboxMsg = "Harap pilih bahwa Anda menerima untuk dihubungi melalui telemarketing";

    // set the starting error message
    myCheckbox.setCustomValidity(myCheckboxMsg);

    // attach checkbox handler to toggle error message
    myCheckbox.addEventListener("change", function(e) {
      this.setCustomValidity(this.validity.valueMissing ? myCheckboxMsg : "");
    }, false);

  }, false);
