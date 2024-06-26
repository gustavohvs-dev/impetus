<?php 

namespace app\utils;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

/**
 * Requisitos:
 * Caso for usar Gmail é necessário ir em Google Accounts -> Segurança -> Ativar autenticação de dois fatores
 * Crie um App Password para o utilizar
 */

class PHPMailerManager
{
    public function send($setFromName, array $addresses, $subject, $body, $alternativeBody = null)
    {
        //Create an instance; passing `true` enables exceptions
        $mail = new PHPMailer(true);

        try {
            //Server settings
            $mail->SMTPDebug = SMTP::DEBUG_SERVER;
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true; 
            $mail->Username = "someemail@gmail.com";
            $mail->Password = "xxxx xxxx xxxx xxxx";
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;   
            $mail->Port = 465;

            //Recipients
            $mail->setFrom($mail->Username, $setFromName);
            foreach($addresses as $address){
                $mail->addAddress($address);
            }
            //$mail->addReplyTo('gustavohvs.dev@gmail.com', 'Information');
            //$mail->addCC('cc@example.com');
            //$mail->addBCC('bcc@example.com');

            //Attachments
            //$mail->addAttachment('/var/tmp/file.tar.gz');
            //$mail->addAttachment('/tmp/image.jpg', 'new.jpg');

            //Content
            $mail->isHTML(true);
            $mail->Subject = $subject;
            $mail->Body    = $body;
            if($alternativeBody != null){
                $mail->AltBody = $alternativeBody;
            }

            $mail->send();
            return [
                "status" => 1,
                "info" => "Message has been sent"
            ];
        } catch (Exception $e) {
            return [
                "status" => 0,
                "info" => "Failed to send message",
                "error" => "Message could not be sent. Mailer Error: {$mail->ErrorInfo}"
            ];
        }
    }
}

$gmail = new PHPMailerManager();
$gmail->send("IMPETUS FRAMEWORK", ['teste@mail.com'], "EMAIL BOT TEST", "This is just a <b>test</b>. Please ignore it.");