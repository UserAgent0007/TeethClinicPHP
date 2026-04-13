<?php
ob_start(); // <- буфер для збору вього
session_start(); // ← у головному файлі, до будь-якого HTML
?>
<?php 
    require_once("./layout/header.php");
    
    require_once("./views/registration.php");
    require_once("./views/login.php");
?>

<?php
    if (isset($_GET["action"])){

        $path = "views/" . $_GET["action"] . ".php";
   

        if (file_exists($path) && $_GET["action"] != "registration" && $_GET["action"] != "login"){   
            
            require_once($path);
        }

        else{
            require_once("./views/main.php");
        }
    }
    else{
        require_once("./views/main.php");
    }
?>

<?php
    require_once("./layout/footer.php")
?>
