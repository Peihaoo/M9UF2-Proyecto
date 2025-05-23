<?php
    session_start(); 
    
    function actualitzaDates($db){
        $data = date('Y/m/d H:i:s');
        try{
            $sql = 'UPDATE usuari SET lastSignIn = ? WHERE username = ?';
            $update = $db->prepare($sql);
            $update->execute(array($data,$_SESSION['user']));   
        }catch(PDOException $e){
            echo "Error BDs: ".$e->getMessage();
        }
    }
    require_once('conectaDB.php');
    if($_SERVER["REQUEST_METHOD"] == "POST"){
        $identificador = $_POST["usuariMail"];
        $contrasenya = $_POST["contrasenya"];

        try{
            $sql = 'SELECT * FROM usuari WHERE mail = ? OR username = ?';
            $preparada = $db->prepare($sql);
            $preparada->execute(array($identificador, $identificador));
            $user = $preparada ->fetch(PDO::FETCH_ASSOC);
            if($preparada->rowCount() > 0){
                if(password_verify($contrasenya, $user['passHash']) == true){
                    $_SESSION['user'] = $user['username'];
                    $_SESSION['mail'] = $user['mail'];
                    if($user['active'] == 1){
                        actualitzaDates($db);
                        header('Location: home.php');
                    } else{
                        header('Location: activa.php');
                    }
                } else{
                    echo "<script>
                    alert('Dades incorrectes.');
                    window.location.href = '../index.html';
                    </script>";
                    exit();
                }
            }
            else{
                echo "<script>
                    alert('Dades incorrectes.');
                    window.location.href = '../index.html';
                    </script>";
                    exit();
            }
        }catch(PDOException $e){
            print_r( $db->errorinfo());
        }
    }
?>