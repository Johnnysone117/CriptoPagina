<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;
class Mailer{
    function enviarEmail($email,$asunto,$cuerpo){

            require_once 'configuracion.php';
            require 'PHPmailer/PHPmailer/src/PHPmailer.php';
            require 'PHPmailer/PHPmailer/src/SMTP.php';
            require 'PHPmailer/PHPmailer/src/Exception.php';

        $mail = new PHPMailer(true);
        try{
            $mail->SMTPDebug = SMTP::DEBUG_OFF;
            $mail->isSMTP();
            $mail->Host = MAIL_HOST;  
            $mail->SMTPAuth = true;
            $mail->Username=MAIL_USER;
            $mail->Password=MAIL_PASS;
            $mail->SMTPSecure=PHPMailer::ENCRYPTION_SMTPS;
            $mail->Port=MAIL_PORT;
            //CORREO DE QUIEN ENVIA Y DESTINATARIO,CCP
            $mail->setFrom(MAIL_USER,'Johnny H.T.');
            $mail->addAddress($email);
            $mail->addCC('johnnyhernandeztorres@gmail.com');

            //Contenido

            $mail->isHTML(true);
            $mail->Subject=$asunto;
            $mail->Body= $cuerpo;
            //$mail->Body = mb_convert_encoding($cuerpo, 'ISO-8859-1', 'UTF-8');

           if($mail->send()){
                return true;
           }else{

            return false;
           }
            
        }catch(Exception $e){
            echo "No se pudo enviar el mensaje.Error de envío:{$mail->ErrorInfo}";
            return false;
        }
    }

}

