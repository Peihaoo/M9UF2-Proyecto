<?php
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
    function cambiaPassword($db,$contrasenya){
        $passHash = password_hash($contrasenya, PASSWORD_BCRYPT);
        try{
            $sql = 'UPDATE usuari SET passHash = ?, resetPassCode = ?, resetPassExpiry = ?,  WHERE mail = ?';
            $update = $db->prepare($sql);
            $update->execute(array($passHash, NULL, NULL, $_SESSION['mailResPassword']));   
        }catch(PDOException $e){
            echo "Error BDs: ".$e->getMessage();
        }
    }
    function actualitzaData($db){
        try{
            $sql = 'UPDATE usuari SET resetPassExpiry = ? WHERE mail = ?';
            $update = $db->prepare($sql);
            $update->execute(array(NULL, $_SESSION['mailResPassword']));   
        }catch(PDOException $e){
            echo "Error BDs: ".$e->getMessage();
        }
    }
    session_start(); 
    require_once('conectaDB.php');
    use  PHPMailer\PHPMailer\PHPMailer;

    if (!isset($_SESSION['mailResPassword'])) {
        header("Location: ../index.html"); 
        exit();
    }
    
    if($_SERVER["REQUEST_METHOD"] == "POST"){
        $contrasenya = $_POST["contrasenya"];
        $Vcontrasenya = $_POST["verifyContrasenya"];
         
        coincideixContrasenya($contrasenya, $Vcontrasenya);
        if(validarContrasenya($contrasenya) == false){
            echo "<script>
                alert('La contrasaenya no compleix amb els requisits:\\n- Una majúscula\\n- Una minúscila\\n- Un nombre\\n- Un símbol\\n- Longitud (6-20)');
                window.location.href = './../HTML/registre.html';
                </script>";
            exit();
        }
        cambiaPassword($db,$contrasenya);
        //"composer require phpmailer/phpmailer" en el cmd, estando en la ruta de este programa
        require  '../vendor/autoload.php';
        $mail  =  new  PHPMailer();
        $mail->IsSMTP();
        //Configuració  del  servidor  de  Correu
        //Modificar  a  0  per  eliminar  msg  error
        $mail->SMTPDebug  =  0; //1 para ver errores, 2 para ver todo
        $mail->SMTPAuth  =  true;
        $mail->SMTPSecure  =  'tls';
        $mail->Host  =  'smtp.gmail.com';
        $mail->Port  =  587;

        //Credencials  del  compte  GMAIL
        $mail->Username  =  'peihao.guoy@educem.net';
        $mail->Password  =  'qynd ouql vvlx yebm';

        //Dades del correu electrònic
        $mail->SetFrom('peihao.guoy@educem.net','Peihao');
        $mail->Subject='Contrasenya Modificada!'; //Asunto
        $mail->MsgHTML('La teva contrasenya per Twitter ha sigut actualitzada amb éxit!'); //Mensaje
        //$mail->addAttachment("fitxer.pdf"); //Archivos adjuntos

        //Destinatari
        $address=$_SESSION['mailResPassword'];
        $mail->AddAddress($address,'Holi');
        
        //Enviament
        $result=$mail->Send();
        if(!$result){
            echo'Error:'.$mail->ErrorInfo;
        }else{
            actualitzaData($db);
            header('Location: ../index.html');
        }
    }
?>