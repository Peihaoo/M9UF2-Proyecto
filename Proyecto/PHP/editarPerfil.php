<?php
    require_once('conectaDB.php');
    session_start(); 
    if (!isset($_SESSION['user'])) {
        header("Location: ../index.html"); 
        exit();
    }
    function comprovaFormatImg(){
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $fileType = $_FILES['pfp']['type'];
            $allowedImgTypes = ['image/jpeg', 'image/png'];
            if (!in_array($fileType, $allowedImgTypes)) {
                die("La imatge no és PNG ni JPG!");
            }
        }
    }
    if($_SERVER["REQUEST_METHOD"] == "POST"){
        $edat = $_POST["edat"];
        $ubi = $_POST["ubicacio"];
        $bio = $_POST["biografia"];

        $carpetaImg="../img/";
        comprovaFormatImg();
        $tempPath = $_FILES['pfp']['tmp_name'];
        $nomPfp = $_FILES['pfp']['name'];
        $nouPath = $carpetaImg.$nomPfp;
        if(move_uploaded_file($tempPath, $nouPath)){
            try{
                $sql = 'UPDATE usuari SET imgPerfil = ? , Biografia = ?, Ubicacio = ?, Edat = ? WHERE username = ?';
                $update = $db->prepare($sql);
                $update->execute(array($nouPath, $bio, $ubi, $edat, $_SESSION['user']));
    
            }catch(PDOException $e){
                print_r( $db->errorinfo());
            }
            header('Location: ./perfil.php');
        }
        else{
            echo "Error al pujar imatge " . $nomImatgeNova . "<br>";
        }
        
    }
?>