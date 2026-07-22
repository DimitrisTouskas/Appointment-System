let baseUrl = document.body.dataset.baseUrl;
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

let formGrapper = document.getElementById('registerForm')
let formListener = formGrapper.addEventListener("submit" , onRegisterSubmit);


async function onRegisterSubmit(event){
    event.preventDefault();
    if(validateForm()=== false){
        return false;
    }else{
       let usernameRecieved = document.getElementById("username").value;
       let fistnameRecieved = document.getElementById("firstname").value;
       let lastnameRecieved = document.getElementById("lastname").value;
       let emailRecieved = document.getElementById("email").value;
       let passwordRecieved = document.getElementById("password").value;
       let password2Recieved = document.getElementById("password2").value;
       let tokenRecieved = document.getElementById("security_token").value;
       
       let body = new URLSearchParams({username:usernameRecieved,firstname:fistnameRecieved , lastname:lastnameRecieved,
         email:emailRecieved, password:passwordRecieved,password2:password2Recieved, security_token:tokenRecieved});
       
       let res = await fetch(`${baseUrl}/register` , {method:'POST' , body});
       let result = await res.json();

       if(result['status']=== "success"){
        sessionStorage.setItem("message" , result.message);
        window.location = `${baseUrl}/appointments`;

       }else{
        alert(result.message);
       }


    }
}