<?php
    session_start(); 
    if (!isset($_SESSION['user'])) {
        header("Location: ../index.html"); 
        exit();
    }
    function activaUser($db){
        try{
            $sql = 'UPDATE usuari SET active = ?, activationCode = ? WHERE username = ?';
            $update = $db->prepare($sql);
            $update->execute(array(1,NULL,$_SESSION['user']));   
        }catch(PDOException $e){
            echo "Error BDs: ".$e->getMessage();
        }
    }
    require_once('conectaDB.php');
    if($_SERVER["REQUEST_METHOD"] == "GET"){
        $code = $_GET["code"];
        try{
            $sql = 'SELECT * FROM usuari WHERE username = ?';
            $preparada = $db->prepare($sql);
            $preparada->execute(array($_SESSION['user']));
            $user = $preparada ->fetch(PDO::FETCH_ASSOC);
            if($user['activationCode'] == $code){
                activaUser($db);
                echo "<script>
                    alert('Compte activat!);
                    window.location.href = './home.php';
                    </script>";
                    exit();
            }
        }catch(PDOException $e){
            echo "Error BDs: ".$e->getMessage();
        }
    }
?>