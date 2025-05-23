<?php
    session_start(); 
    if (!isset($_SESSION['user'])) {
        header("Location: ../index.html"); 
        exit();
    }
    require_once('conectaDB.php');
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="../img/favicon.png">
    <title>Twitter - Home</title>
    <link rel="stylesheet" href="../CSS/home.css">
</head>
<body>
    <!-- Header -->
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
    <!-- Contenido principal -->
    <div class="container">
        <h1>Hola, <?php echo $_SESSION['user']; ?>!</h1>
        <br>
        <br>
        <?php
            try{
                $sql = "SELECT * FROM publicacio 
                        ORDER BY dataPublicacio DESC 
                        LIMIT 10 ";
                $preparada = $db->prepare($sql);
                $preparada->execute();
                if($preparada){
                    foreach($preparada as $publicacio){
                        echo '<div class="autor"> <strong>' .'@'. htmlspecialchars($publicacio['autor']) . '</strong></div>';
                        echo '<div class="post">';
                            echo '<img src="' . htmlspecialchars($publicacio['imgPath']) . '" alt="Publicació" width="300px" height="300px">';
                            echo '<div class="post-info">';
                                echo '<div class="fecha">' . date("d M Y", strtotime($publicacio['dataPublicacio'])) . '</div>';
                                echo '<div class="descripcio">' . htmlspecialchars($publicacio['descripcio']) . '</div>';
                                echo '<div class="likes">❤ ' . (int)$publicacio['qttLikes'];
                                    ?>
                                    <div class="Interacciones">
                                        <form action="darLike.php" method="post">
                                            <input type="hidden" name="post_id" value="<?php echo "$publicacio[idPublicacio]"; ?>">
                                            <input type="hidden" name="nomFitxerOrigen" value="home.php">
                                            <button type="submit" class="like-btn">❤</button>
                                        </form>
                                        <form action="veurePublicacio.php" method="post" style="display:inline;">
                                            <input type="hidden" name="id" value="<?php echo "$publicacio[idPublicacio]"; ?>">
                                            <button type="submit" class="comment-button" title="Ver comentarios">💬</button>
                                        </form>
                                    </div>
                                    <?php
                                echo '</div>';
                            echo '</div>';
                        echo '</div>';
                        echo '<br>';
                        echo '<br>';
                    }
                }
            }catch(PDDException $e){
            echo "Error amb la BDs: ". $e->getMessage();
            }
        ?>
        <div class="footer-fixed">
            <form action="./ferPublicacio.php">
                <button type="submit">Fer una publicació</button>
            </form>
            <form action="perfil.php">
                <button type="submit">Anar al teu perfil</button>
            </form>
            <form action="logout.php">
                <button type="submit">Log out</button>
            </form>
        </div>
    </div>
</body>
</html>
