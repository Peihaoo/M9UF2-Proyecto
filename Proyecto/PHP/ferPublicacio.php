<?php
    session_start(); 
    if (!isset($_SESSION['user'])) {
        header("Location: ../index.html"); 
        exit();
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Twitter - Publish</title>
    <link rel="icon" type="image/png" href="../img/favicon.png">
    <link rel="stylesheet" href="/CSS/ferPublicacio.css">
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
      <div class="form-container">
        <h2>Crear Nueva Publicación</h2>
        <form action="./publicaPost.php" method="POST" enctype="multipart/form-data">
            <!-- Imagen -->
            <label for="imgPath">Imagen:</label>
            <input id="imgPath" name="imgPath" type="file"/>

            <!-- Descripción -->
            <label for="descripcio">Descripción:</label>
            <textarea name="descripcio" id="descripcio" rows="4" placeholder="Escribe algo..."></textarea>

            <input type="submit" value="Publicar">
        </form>
    </div>
</body>
</html>