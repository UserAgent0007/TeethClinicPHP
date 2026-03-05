let bod = document.body;

let reg_button = document.getElementById("reg");
let login_button = document.getElementById("log");

let close_reg = document.getElementById("reg_close");
let close_log = document.getElementById("log_close")

// reg_button.addEventListener("click", function (){
//     // bod.classList.add ("overf-hid");
//     bod.style.overflow = 'hidden';
// });

login_button.addEventListener("click", function (){
    // bod.classList.add ("overf-hid");
    bod.style.overflow = 'hidden';
});

document.addEventListener('click', function(e){
    if (e.target.id === "reg_close"){
        bod.style.overflow = 'auto';
    }
    if (e.target.id === "reg"){
        bod.style.overflow = 'hidden';
    }
});

close_log.addEventListener("click", function (){
    // bod.classList.remove ("overf-hid")
    bod.style.overflow = 'auto';
});