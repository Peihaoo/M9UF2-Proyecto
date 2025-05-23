<?php
    session_start(); 
    if (!isset($_SESSION['user'])) {
        header("Location: ../index.html"); 
        exit();
    }
    function generaHash(){
        $randNum = rand(10,1000000);
        return hash('sha256', $randNum);
    }
    require_once('conectaDB.php');
    $hash = generaHash();
    
    use  PHPMailer\PHPMailer\PHPMailer;
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
    $mail->Subject='Activa el teu compte.'; //Asunto
    $mail->MsgHTML("http://localhost/PHP/activa2.php?code=".$hash."&mail=".$_SESSION['mail']); //Mensaje
    //$mail->addAttachment("fitxer.pdf"); //Archivos adjuntos

    //Destinatari
    $address=$_SESSION['mail'];
    $mail->AddAddress($address,$_SESSION['user']);
    
    //Enviament
    $result=$mail->Send();
    if(!$result){
        echo'Error:'.$mail->ErrorInfo;
    }else{
        try{
            $sql = 'UPDATE usuari SET activationCode = ? WHERE username = ?';
            $update = $db->prepare($sql);
            $update->execute(array($hash, $_SESSION['user']));
    
        }catch(PDOException $e){
            print_r( $db->errorinfo());
        }
        echo "Correu enviat. Verifica el teu correu!";
    }
?>