let baseUrl = document.body.dataset.baseUrl;
function validateForm(){
    let validationDate = document.getElementById("appointment_date").value.trim();
    if (validationDate ==""){
        alert("Date cannot be empty");
        return false;
    }

    let validationTime = document.getElementById("appointment_time").value.trim();
    if (validationTime ==""){
        alert("Time cannot be empty");
        return false;
    }

    let trimmedDate = new Date().toISOString().split('T')[0]
    if ( validationDate < trimmedDate){
        alert("You use previous date");
        return false;
    }
    
    let trimmedTime = new Date().toISOString().split('T')[1].substring(0,5);
    if ( validationTime < trimmedTime && validationDate == trimmedDate ){
        alert("You use previous time");
        return false;
    }
    return true;
}


let formGrapper = document.getElementById('editForm')
let formListener = formGrapper.addEventListener("submit" , onEditSubmit);


async function onEditSubmit(event){
    event.preventDefault();
    if(validateForm()=== false){
        return false;
    }else{
       let dateRecieved = document.getElementById("appointment_date").value;
       let timeRecieved = document.getElementById("appointment_time").value;
       let notesRecieved = document.getElementById("appointment_notes").value;
       let idRecieved = document.getElementById("appointment_id").value;
       let statusRecieved = document.getElementById("status").value;
       let tokenRecieved = document.getElementById("security_token").value;
       
       let body = new URLSearchParams({appointment_date:dateRecieved, appointment_time:timeRecieved, appointment_notes:notesRecieved ,
         appointment_id:idRecieved , status:statusRecieved , security_token:tokenRecieved});
       
       let res = await fetch(`${baseUrl}/appointments/edit` , {method:'POST' , body});
       let result = await res.json();

       if(result['status']=== "success"){
        sessionStorage.setItem("message" , result.message);
        window.location = `${baseUrl}/appointments`;

       }else{
        alert(result.message);
       }


    }
}