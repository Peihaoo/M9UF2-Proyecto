<?php
    //Funcions
    function validarContrasenya($password) {
        $valid = true;
        // Verificar longitud (entre 6 i 20 caracters)
        if (strlen($password) < 6 || strlen($password) > 20) {
            $valid = false;
        }
        // Verificar si conté almenys una majúscula
        if (!preg_match('/[A-Z]/', $password)) {
            $valid = false;   
        }
        // Verificar si conté almenys una minúscula
        if (!preg_match('/[a-z]/', $password)) {
            $valid = false;
        }
        // Verificar si conté almenys un nombre
        if (!preg_match('/[0-9]/', $password)) {
            $valid = false;
        }
        // Verificar si conté almenys un símbol
        if (!preg_match('/[!@#$%^&*(),.?":{}|<>]/', $password)) {
            $valid = false;
        }
        
        return $valid;
    }
    function coincideixContrasenya($contrasenya1, $contrasenya2){
        if($contrasenya1 != $contrasenya2){
            echo "<script>
                alert('Les contrasenyes no coincideixen.');
                window.location.href = './../HTML/registre.html';
                </script>";
            exit();
        }
    }
    function validarUser($usuari, $db){
        try{
            $sql = 'SELECT * FROM usuari WHERE username = ?';
            $preparada = $db->prepare($sql);
            $preparada->execute(array($usuari));
            if($preparada->rowCount() > 0){
                echo "<script>
                    alert('Usuari no disponible.');
                    window.location.href = './../HTML/registre.html';
                    </script>";
                exit();
            }
        }catch(PDOException $e){
            print_r( $db->errorinfo());
        }
    }
    function validarMail($mail, $db){
        try{
            $sql = 'SELECT * FROM usuari WHERE mail = ?';
            $preparada = $db->prepare($sql);
            $preparada->execute(array($mail));
            if($preparada->rowCount() > 0){
                echo "<script>
                    alert('Mail no disponible.');
                    window.location.href = './../HTML/registre.html';
                    </script>";
                exit();
            }
        }catch(PDOException $e){
            print_r( $db->errorinfo());
        }
    }
    function insert($db, $email, $user, $passHash, $nom, $cognom, $active){
        try{
            $sql = "INSERT INTO usuari(mail, username, passHash, userFirstName, userLastName, creationDate, removeDate, lastSignIn, active, activationCode, resetPassExpiry, resetPassCode) 
                    VALUES('$email','$user','$passHash','$nom','$cognom', now(), NULL, NULL, $active, NULL, NULL, NULL)";
            $insert = $db->query($sql);
            if($insert){
                echo "<script>
                        alert('Registro insertado con éxito');
                        window.location.href = '/../index.html';
                        </script>";
                exit();
            }else{
                print_r( $db->errorinfo());
            }
        }catch(PDOException $e){
            echo 'Error amb la BDs: ' . $e->getMessage();
        }
    }

    //Main
    require_once('conectaDB.php');
    if($_SERVER["REQUEST_METHOD"] == "POST"){
        $user = $_POST["usuari"];
        $email = $_POST["email"];
        $nom = $_POST["nom"];
        $cognom = $_POST["cognom"];
        $contrasenya = $_POST["contrasenya"];
        $Vcontrasenya = $_POST["verifyContrasenya"];
        $active = 0;
         
        coincideixContrasenya($contrasenya, $Vcontrasenya);
        if(validarContrasenya($contrasenya) == false){
            echo "<script>
                alert('La contrasaenya no compleix amb els requisits:\\n- Una majúscula\\n- Una minúscila\\n- Un nombre\\n- Un símbol\\n- Longitud (6-20)');
                window.location.href = './../HTML/registre.html';
                </script>";
            exit();
        }
        if(filter_var($email, FILTER_VALIDATE_EMAIL) == false){
            echo "<script>
                alert('Format de mail incorrecte');
                window.location.href = './../HTML/registre.html';
                </script>";
            exit();
        }
        $passHash = password_hash($contrasenya, PASSWORD_BCRYPT);
        validarUser($user, $db);
        validarMail($email, $db);
        insert($db, $email, $user, $passHash, $nom, $cognom, $active);
    }
?>