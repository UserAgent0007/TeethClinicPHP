document.addEventListener("DOMContentLoaded", function()
{
    if (localStorage.getItem("role") == "admin")
    {
        let but = document.getElementById("add_option");
        but.classList.remove("add_no_show")
    }
})