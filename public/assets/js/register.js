function validateForm(){
    let validationUsername = document.getElementById("username").value.trim();
    if (validationUsername ==""){
        alert("Username cannot be empty");
        return false;
    }

    let validationEmail = document.getElementById("email").value.trim();
    if (validationEmail ==""){
        alert("Email cannot be empty");
        return false;
    }
    let validationPassword = document.getElementById("password").value.trim();
    if (validationPassword ==""){
        alert("Password cannot be empty");
        return false;
    }
    let validationPassword2 =document.getElementById("password2").value.trim();
    if (validationPassword2 ==""){
        alert("Confirm password cannot be empty");
    return false;
    }
    if (validationPassword!==validationPassword2){
        alert("Passwords do not match ")
        return false;
    }

    return true;
}