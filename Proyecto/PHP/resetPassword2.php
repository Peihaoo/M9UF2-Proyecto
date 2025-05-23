<?php
    session_start(); 
    if (!isset($_SESSION['mailResPassword'])) {
        header("Location: ../index.html"); 
        exit();
    }
    require_once('conectaDB.php');
    $dataActual = new DateTime();
    $dataActual = $dataActual->format('Y/m/d H:i:s');

    try{
        $sql = 'SELECT * FROM usuari WHERE mail = ?';
        $preparada = $db->prepare($sql);
        $preparada->execute(array($_SESSION['mail']));
        $user = $preparada ->fetch(PDO::FETCH_ASSOC);

        $dataExpiry = new DateTime($user['resetPassExpiry']);

        if($dataActual > $dataExpiry){
            echo "<script>
                    alert('Temps per canviar contrasenya caducat!.');
                    window.location.href = '../index.html';
                    </script>";
                    exit();
        }
    }catch(PDOException $e){
        print_r( $db->errorinfo());
    }
    if($_SERVER["REQUEST_METHOD"] == "GET"){
        $code = $_GET["code"];
        try{
            $sql = 'SELECT * FROM usuari WHERE mail = ?';
            $preparada = $db->prepare($sql);
            $preparada->execute(array($_SESSION['mailResPassword']));
            $user = $preparada ->fetch(PDO::FETCH_ASSOC);
            if($user['resetPassCode'] == $code){
                header('Location: ../HTML/newPassword.html');
                exit();
            }
        }catch(PDOException $e){
            echo "Error BDs: ".$e->getMessage();
        }
    }
?>