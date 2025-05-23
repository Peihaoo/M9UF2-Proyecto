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
        $descripcio = $_POST['descripcio'];

        $autor = $_SESSION['user'];

        $carpetaImg="../img/";
        $tempPath = $_FILES['imgPath']['tmp_name'];
        $nomPfp = $_FILES['imgPath']['name'];
        $nouPath = $carpetaImg.$nomPfp;

        if(move_uploaded_file($tempPath, $nouPath)){
            try{
                $sql = "INSERT INTO publicacio (autor, imgPath, qttLikes, descripcio, dataPublicacio) 
                        VALUES('$autor', '$nouPath', 0, '$descripcio', now())";
                $insert = $db->query($sql);
                if($insert){
                echo "<script>
                        alert('Publicació feta amb èxit');
                        window.location.href = './home.php';
                        </script>";
                exit();
                }else{
                    print_r( $db->errorinfo());
                }
            }catch(PDOException $e){
                print_r( $db->errorinfo());
            }
        }
        else{
            echo "Error al pujar imatge " . $nomImatgeNova . "<br>";
        }
    }
?>