function validateForm(){
    let validationUsername = document.getElementById("username").value.trim();
    if (validationUsername ==""){
        alert("Username cannot be empty");
        return false;
    }
    let validationPassword = document.getElementById("password").value.trim();
    if (validationPassword ==""){
        alert("Password cannot be empty");
        return false;
    }

    return true;
}