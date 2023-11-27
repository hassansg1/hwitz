function togglePasswordVisibility(className = 'password-field') {
    var passwordField = document.getElementById(className);
    if (passwordField.type === "password") {
      passwordField.type = "text";
    } else {
      passwordField.type = "password";
    }
  }