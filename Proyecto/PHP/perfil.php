<?php
    session_start(); 
    if (!isset($_SESSION['user'])) {
        header("Location: ../index.html"); 
        exit();
    }
    require_once('conectaDB.php');
    try{
        $sql = 'SELECT imgPerfil, Biografia, Ubicacio, Edat FROM usuari WHERE username = ?';
        $preparada = $db->prepare($sql);
        $preparada->execute(array($_SESSION['user']));                
        $user = $preparada ->fetch(PDO::FETCH_ASSOC);
        if($user){
            $bio = $user['Biografia'];
            $ubi = $user['Ubicacio'];
            $edat = $user['Edat'];
            $rutaPfp = $user['imgPerfil'];
        }
    }catch(PDDException $e){
        echo "Error amb la BDs: ". $e->getMessage();
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="../img/favicon.png">
    <title>Twitter - Profile</title>
    <link rel="stylesheet" href="../CSS/perfil.css">
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
<div class="profile-container">
    <!-- Foto de perfil -->
    <section class="profile-info">
      <img src="<?php echo"$rutaPfp" ?>" alt="Foto de perfil" class="profile-picture" width="350" height="350">
      
      <!-- Datos de usuario -->
      <div class="user-details">
        <p class="location">Ubicación: <?php echo"$ubi" ?></p>
        <p class="age">Edad: <?php echo"$edat" ?></p>
        <p class="bio"><?php echo"$bio" ?></p>
      </div>
    </section>
    
    <!-- Publicaciones -->
     <h2>Publicacions.</h2>
    <?php
      try{
        $sql = "SELECT * FROM publicacio WHERE autor = ?
                ORDER BY dataPublicacio DESC";
        $preparada = $db->prepare($sql);
        $preparada->execute(array($_SESSION['user']));
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
                          <form action="veurePublicacio.php" method="post" style="display:inline;">
                            <input type="hidden" name="id" value="<?php echo "$publicacio[idPublicacio]"; ?>">
                            <button type="submit" class="comment-button" title="Ver comentarios">💬</button>
                          </form>
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
  </div>
</body>
</html>