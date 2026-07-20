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


let formGrapper = document.getElementById('createForm')
let formListener = formGrapper.addEventListener("submit" , onCreateSubmit);


async function onCreateSubmit(event){
    event.preventDefault();
    if(validateForm()=== false){
        return false;
    }else{
       let tokenRecieved = document.getElementById("security_token").value;
       let dateRecieved = document.getElementById("appointment_date").value;
       let timeRecieved = document.getElementById("appointment_time").value;
       let notesRecieved = document.getElementById("appointment_notes").value;
       
       let body = new URLSearchParams({appointment_time:timeRecieved, appointment_date:dateRecieved, security_token:tokenRecieved , appointment_notes:notesRecieved});
       
       let res = await fetch(`${baseUrl}/appointments/create`, {method:'POST' , body});
       let result = await res.json();

       if(result['status']=== "success"){
        window.location = `${baseUrl}/appointments`;

       }else{
        alert(result.message);
       }


    }
}