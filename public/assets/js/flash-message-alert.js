flash_message = document.getElementById("flash-message");

let storageMessage = sessionStorage.getItem("message");

flash_message.textContent = storageMessage;

if(storageMessage){
    flash_message.style.display='flex';
setTimeout(function() {
   flash_message.style.display='none'; 
}, 3000);
sessionStorage.removeItem("message");
}