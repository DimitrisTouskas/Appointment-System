let baseUrl = document.body.dataset.baseUrl;
function validateForm(){

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
    return true;
}

let formGrapper = document.getElementById('loginForm')
let formListener = formGrapper.addEventListener("submit" , onLoginSubmit);


async function onLoginSubmit(event){
    event.preventDefault();
    if(validateForm()=== false){
        return false;
    }else{
       let tokenRecieved = document.getElementById("security_token").value;
       let emailRecieved = document.getElementById("email").value;
       let passwordRecieved = document.getElementById("password").value;
       
       let body = new URLSearchParams({email:emailRecieved, password:passwordRecieved, security_token:tokenRecieved});
       
       let res = await fetch(`${baseUrl}/login` , {method:'POST' , body});
       let result = await res.json();

       if(result['status']=== "success"){
        window.location = `${baseUrl}/appointments`;

       }else{
        alert(result.message);
       }


    }
    

}