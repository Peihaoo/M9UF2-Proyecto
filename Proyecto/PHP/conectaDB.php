<?php
    $cadena_connexio = 'mysql:dbname=projectephp;host=localhost:3335';
    $usuari = 'root';
    $passwd = '';
    try{
        //Ens connectem a la BDs
        // $db = new PDO($cadena_connexio, $usuari, $passwd);
        $db = new PDO($cadena_connexio, $usuari, $passwd, 
                        array(PDO::ATTR_PERSISTENT => true));
        // echo "Connexió a la BD establerta.";
    }catch(PDOException $e){
        echo 'Error amb la BDs: ' . $e->getMessage();
    }
    //Per tallar la connexió a la BDs
    // $db = null;
?>