document.addEventListener("click" , handleStatusClick);


async function handleStatusClick(event){
    if (!event.target.dataset.status) return false; 
    event.preventDefault();
    let dropdownBox= event.target.closest(".dropdown");
    const btn = dropdownBox.querySelector("[data-appointment-id]");
    const value = btn.dataset.appointmentId; 
    const csrf = document.querySelector("[data-csrf-token]").dataset.csrfToken;
    const status1 = event.target.dataset.status;

    let body = new URLSearchParams({appointment_id:value , status:status1 , security_token:csrf});
    let res = await fetch('/appointment-system/public/appointments/update-status' , {method:'POST' , body});
    let result = await res.json();

    if(result['status']=== "success"){
        window.location = '/appointment-system/public/appointments';

       }else{
        alert(result.message);
       }
}


