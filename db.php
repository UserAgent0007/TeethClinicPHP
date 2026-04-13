<?php
    $link = mysqli_connect("localhost", "root", "", "teethclinic");

    if (!$link) {
        die("DB error: " . mysqli_connect_error());
    }
?>