<?php
    session_start(); 
    if (!isset($_SESSION['user'])) {
        header("Location: ../index.html"); 
        exit();
    }
    require_once('conectaDB.php');
    function muestraComentaris($idPubli, $db){
        try{
            $sql2 = "SELECT * FROM comentari WHERE idPublicacio = ?";
            $preparada2 = $db->prepare($sql2);
            $preparada2->execute(array($idPubli));
            $comentaris = $preparada2->fetchAll(PDO::FETCH_ASSOC);

            echo '<div class="comentaris-container">';
            echo '<h3>Comentaris</h3>';
            if (count($comentaris) === 0) {
                echo '<div class="comentari">Encara no hi ha comentaris. Sigues el primer!</div>';
            }else {
                foreach($comentaris as $comentari){
                    echo '<div class="comentari">';
                    echo '<div class="comentari-autor"><strong>@' . htmlspecialchars($comentari['autor']) . '</strong></div>';
                    echo '<div class="comentari-text">' . htmlspecialchars($comentari['textComentari']) . '</div>';
                    echo '<div class="comentari-data">' . date("d M Y H:i", strtotime($comentari['dataComentari'])) . '</div>';
                    echo '</div>';
                    echo "<br>";
                }
                echo '</div>';
            }
        }catch(PDDException $e){
            echo "Error amb la BDs: ". $e->getMessage();
        }
    }
?>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="../img/favicon.png">
    <title>Twitter - Comment</title>
    <link rel="stylesheet" href="../CSS/perfil.css">
</head>
<body>
    <header class="header">
      <a href="./home.php" id="logo-link">
        <img src="../img/logo.png" alt="Logo" class="logo" width="50px" height="50px">
      </a>
      <a href="./perfil.php">
        <div class="user-name">
            <p>@<?php echo $_SESSION['user']; ?></p>
        </div>
      </a>
    </header>
<?php
    if($_SERVER["REQUEST_METHOD"] == "POST"){
        $idPubli = $_POST['id'];
        try{
            $sql = "SELECT * FROM publicacio WHERE idPublicacio = ?";
            $preparada = $db->prepare($sql);
            $preparada->execute(array($idPubli));
            if($preparada){
                foreach($preparada as $publicacio){
                    echo '<div class="autor"> <strong>' .'@'. htmlspecialchars($publicacio['autor']) . '</strong></div>';
                    echo '<div class="post">';
                        echo '<img class="post-img" src="' . htmlspecialchars($publicacio['imgPath']) . '" alt="Publicació"  width="300px" height="300px">';
                        echo '<div class="post-info">';
                            echo '<div class="fecha">' . date("d M Y", strtotime($publicacio['dataPublicacio'])) . '</div>';
                            echo '<div class="descripcio">' . htmlspecialchars($publicacio['descripcio']) . '</div>';
                            echo '<div class="likes">❤ ' . (int)$publicacio['qttLikes'];
                            ?>
                            <form action="darLike.php" method="post">
                                <input type="hidden" name="post_id" value="<?php echo "$publicacio[idPublicacio]"; ?>">
                                <input type="hidden" name="nomFitxerOrigen" value="perfil.php">
                                <button type="submit" class="like-btn">❤</button>
                            </form>
                            <form action="insertaComentari.php" method="post" style="display:inline;">
                                <input type="hidden" name="id" value="<?php echo "$publicacio[idPublicacio]"; ?>">
                                <input type="hidden" name="autorComentari" value="<?php echo "$_SESSION[user]"; ?>">
                                <label for="comentari"></label>
                                <textarea name="comentari" rows="4" cols="55"></textarea>
                                <button type="submit" class="comment-button" title="Ver comentarios">💬</button>
                            </form>
                            <?php
                            echo '</div>';
                        echo '</div>';
                        muestraComentaris($idPubli, $db);
                    echo '</div>';
                    echo '<br>';
                    echo '<br>';
                }
            }
      }catch(PDDException $e){
            echo "Error amb la BDs: ". $e->getMessage();
      }
    }
?>
</body>