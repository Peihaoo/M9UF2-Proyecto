<?php
    require_once('conectaDB.php');
    session_start();
    use  PHPMailer\PHPMailer\PHPMailer;
    function generaHash(){
        $randNum = rand(10,1000000);
        return hash('sha256', $randNum);
    }
    function actualitzaData($db, $mail){
        $data = new DateTime();
        $data->modify('+30 minutes');
        $date = $data->format('Y/m/d H:i:s');

        try{
            $sql = 'UPDATE usuari SET resetPassExpiry = ? WHERE mail = ?';
            $update = $db->prepare($sql);
            $update->execute(array($date, $mail));   
        }catch(PDOException $e){
            echo "Error BDs: ".$e->getMessage();
        }
    }
    if($_SERVER["REQUEST_METHOD"] == "POST"){
        $email=$_POST['mail'];
        $hash = generaHash();
        try{
            $sql = 'SELECT * FROM usuari WHERE mail = ?';
            $preparada = $db->prepare($sql);
            $preparada->execute(array($email));
            if($preparada->rowCount() < 0){
                echo "<script>
                    alert('Mail no trobat.');
                    window.location.href = '../index.html';
                    </script>";
                    exit();
            }
        }catch(PDOException $e){
            print_r( $db->errorinfo());
        }
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
        $mail->Subject='Recupera contransenya.'; //Asunto
        $mail->MsgHTML("http://localhost/PHP/resetPassword2.php?code=".$hash."&mail=".$email); //Mensaje
        //$mail->addAttachment("fitxer.pdf"); //Archivos adjuntos

        //Destinatari
        $address=$email;
        $mail->AddAddress($address,'resetPassword');

        //Enviament
        $result=$mail->Send();
        if(!$result){
            echo'Error:'.$mail->ErrorInfo;
        }else{
            try{
                $sql = 'UPDATE usuari SET resetPassCode = ? WHERE mail = ?';
                $update = $db->prepare($sql);
                $update->execute(array($hash, $email));

            }catch(PDOException $e){
                print_r( $db->errorinfo());
            }
            $_SESSION['mailResPassword'] = $email;
            actualitzaData($db, $email);
            echo "Correu enviat. Verifica el teu correu!";
        }
    }
?>