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