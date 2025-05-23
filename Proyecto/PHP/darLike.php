<?php
    require_once 'conectaDB.php';
    function numLikes($idPubli, $db){
        try {
            $sql = "SELECT qttLikes FROM publicacio WHERE idPublicacio = ?";
            $preparada = $db->prepare($sql);
            $preparada->execute(array($idPubli));
            $resultado = $preparada->fetch(PDO::FETCH_ASSOC);
            if($resultado){
                return (int) $resultado['qttLikes'];
            }
        }catch (PDOException $e) {
            echo "Error al actualizar like: " . $e->getMessage();
            exit;
        }
    } 

    if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['post_id'])) {
        $postId = (int) $_POST['post_id'];
        $returnTo = $_POST['nomFitxerOrigen'];
        $qttLikes = numLikes($postId, $db) + 1;
        try {
            $sql = "UPDATE publicacio SET qttLikes = ? WHERE idPublicacio = ?";
            $update = $db->prepare($sql);
            $update->execute(array($qttLikes,$postId));
        } catch (PDOException $e) {
            echo "Error al actualizar like: " . $e->getMessage();
            exit;
        }

        // Redirigir de nuevo a la página anterior
        header("Location: ./$returnTo");
        exit;
    }
?>
