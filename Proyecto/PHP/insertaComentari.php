<?php
    session_start(); 
    if (!isset($_SESSION['user'])) {
        header("Location: ../index.html"); 
        exit();
    }
    require_once('conectaDB.php');
    if($_SERVER["REQUEST_METHOD"] == "POST"){
        $idPublicacio = $_POST['id'];
        $autorComentari = $_POST['autorComentari'];
        $textComentari = $_POST['comentari'];
        try{
            $sql = "INSERT INTO comentari (idPublicacio, autor, textComentari, dataComentari) 
                VALUES (?, ?, ?, NOW())";
            $preparada = $db->prepare($sql);
            $insert = $preparada->execute([$idPublicacio, $autorComentari, $textComentari]);
            if($insert){
                echo '<form id="redirectForm" action="veurePublicacio.php" method="post">';
                echo '<input type="hidden" name="id" value="' . htmlspecialchars($idPublicacio) . '">';
                echo '</form>';
                echo '<script>document.getElementById("redirectForm").submit();</script>';
            exit();
            exit();
            }else{
                print_r( $db->errorinfo());
            }
        }catch(PDDException $e){
            echo "Error amb la BDs: ". $e->getMessage();
        }
    }
?>