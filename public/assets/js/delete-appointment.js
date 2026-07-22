let baseUrl = document.body.dataset.baseUrl;
let deleteFormGrapping= document.getElementById('deleteForm')
let formListener = deleteFormGrapping.addEventListener("submit" , onDeleteSubmit);

async function onDeleteSubmit(event){
    event.preventDefault();
    let appointment_id= document.getElementById("appointment_id").value;
    let security_token = document.getElementById("security_token").value;   

    let body = new URLSearchParams({appointment_id:appointment_id,security_token:security_token});

    let res = await fetch(`${baseUrl}/appointments/delete`, {method:'POST' , body});
    let result = await res.json();

       if(result['status']=== "success"){
        sessionStorage.setItem("message" , result.message);
        window.location = `${baseUrl}/appointments`;

       }else{
        alert(result.message);
       }
}