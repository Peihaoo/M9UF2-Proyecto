<?php
    session_start();
    session_unset(); 
    session_destroy(); 
    header("Location: ../index.html"); 
    //De moment no esborro cookies perquè no hi han jeje
    exit();
?>