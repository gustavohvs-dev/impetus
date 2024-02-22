<?php

require_once("vendor/autoload.php");

//Import PHPMailer classes into the global namespace
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;

function sendMail($mailTitle, $mailTo, $mailReply, $bodyHeader, $bodyContent, $bodyCompany, $bodySlogan, $bodyEmail, $bodyTel){

    //Create a new PHPMailer instance
    $mail = new PHPMailer;

    //Tell PHPMailer to use SMTP
    $mail->isSMTP();

    //Enable SMTP debugging
    // SMTP::DEBUG_OFF = off (for production use)
    // SMTP::DEBUG_CLIENT = client messages
    // SMTP::DEBUG_SERVER = client and server messages
    $mail->SMTPDebug = SMTP::DEBUG_OFF;

    //Set the hostname of the mail server
    $mail->Host = 'mail.emprotege.com';

    //Set the SMTP port number - 587 for authenticated TLS
    $mail->Port = 465;

    //Set the encryption mechanism to use - STARTTLS or SMTPS
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;

    //Whether to use SMTP authentication
    $mail->SMTPAuth = true;

    //Username to use for SMTP authentication - use full email address for gmail
    $mail->Username = "robot@emprotege.com";

    //Password to use for SMTP authentication
    $mail->Password = "7ow?gRDOByD";

    //Set who the message is to be sent from
    $mail->setFrom('robot@emprotege.com', 'EM Protege Robot');

    //Set an alternative reply-to address
    $mail->addReplyTo($mailReply, 'EM Protege Robot');

    //Set who the message is to be sent to
    foreach($mailTo as $mailAdress){
        $mailName = explode("@", $mailAdress);
        $mailName = $mailName[0];
        $mail->addAddress($mailAdress, $mailName);
    }

    //Set the subject line
    $mail->Subject = $mailTitle;

    //Read an HTML message body from an external file, convert referenced images to embedded,
    //convert HTML into a basic plain-text alternative body
    $mail->Body = emailContent($bodyHeader, $bodyContent, $bodyCompany, $bodySlogan, $bodyEmail, $bodyTel);

    //Replace the plain text body with one created manually
    $mail->AltBody = 'Mail Content';

    //Attach an image file
    //$mail->addAttachment('images/phpmailer_mini.png');

    //send the message, check for errors
    if (!$mail->send()) {
        echo "Mailer Error: " . $mail->ErrorInfo;
    } else {
        echo "Message sent!";
    }

}

function emailContent($bodyHeader, $bodyContent, $bodyCompany, $bodySlogan, $bodyEmail, $bodyTel) {

    return '
    
    <!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
    <html>
    <head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" >
    <title>Mailto</title>
    <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700" rel="stylesheet">
    <style type="text/css">
    html { -webkit-text-size-adjust: none; -ms-text-size-adjust: none;}

        @media only screen and (min-device-width: 750px) {
            .table750 {width: 750px !important;}
        }
        @media only screen and (max-device-width: 750px), only screen and (max-width: 750px){
        table[class="table750"] {width: 100% !important;}
        .mob_b {width: 93% !important; max-width: 93% !important; min-width: 93% !important;}
        .mob_b1 {width: 100% !important; max-width: 100% !important; min-width: 100% !important;}
        .mob_left {text-align: left !important;}
        .mob_soc {width: 50% !important; max-width: 50% !important; min-width: 50% !important;}
        .mob_menu {width: 50% !important; max-width: 50% !important; min-width: 50% !important; box-shadow: inset -1px -1px 0 0 rgba(255, 255, 255, 0.2); }
        .mob_center {text-align: center !important;}
        .top_pad {height: 15px !important; max-height: 15px !important; min-height: 15px !important;}
        .mob_pad {width: 15px !important; max-width: 15px !important; min-width: 15px !important;}
        .mob_div {display: block !important;}
        }
    @media only screen and (max-device-width: 550px), only screen and (max-width: 550px){
        .mod_div {display: block !important;}
    }
        .table750 {width: 750px;}
    </style>
    </head>
    <body style="margin: 0; padding: 0;">

    <table cellpadding="0" cellspacing="0" border="0" width="100%" style="background: #f3f3f3; min-width: 350px; font-size: 1px; line-height: normal;">
        <tr>
        <td align="center" valign="top">   			
            <table cellpadding="0" cellspacing="0" border="0" width="750" class="table750" style="width: 100%; max-width: 750px; min-width: 350px; background: #f3f3f3;">
                <tr>
                <td class="mob_pad" width="25" style="width: 25px; max-width: 25px; min-width: 25px;">&nbsp;</td>
                    <td align="center" valign="top" style="background: #ffffff;">

                    <table cellpadding="0" cellspacing="0" border="0" width="100%" style="width: 100% !important; min-width: 100%; max-width: 100%; background: #f3f3f3;">
                        <tr>
                            <td align="right" valign="top">
                            <div class="top_pad" style="height: 25px; line-height: 25px; font-size: 23px;">&nbsp;</div>
                            </td>
                        </tr>
                    </table>

                    <br><br>

                    <table cellpadding="0" cellspacing="0" border="0" width="88%" style="width: 88% !important; min-width: 88%; max-width: 88%;">
                        <tr>
                            <td align="left" valign="top">
                            <div style="height: 75px; line-height: 75px; font-size: 73px;">&nbsp;</div>
                            <font face="Source Sans Pro, sans-serif" color="#1a1a1a" style="font-size: 32px; line-height: 38px; font-weight: 28; letter-spacing: -1.5px;">
                                <span style="font-family: Source Sans Pro, Arial, Tahoma, Geneva, sans-serif; color: #1a1a1a; font-size: 52px; line-height: 60px; font-weight: 300; letter-spacing: -1.5px;">'.$bodyHeader.'</span>
                            </font>
                            <div style="height: 33px; line-height: 33px; font-size: 31px;">&nbsp;</div>
                            <font face="Source Sans Pro, sans-serif" color="#585858" style="font-size: 24px; line-height: 32px;">
                                <span style="font-family: Source Sans Pro, Arial, Tahoma, Geneva, sans-serif; color: #585858; font-size: 24px; line-height: 32px;">'.$bodyContent.'</span>
                            </font>
                            <div style="height: 33px; line-height: 33px; font-size: 31px;">&nbsp;</div>
                            <div style="height: 75px; line-height: 75px; font-size: 73px;">&nbsp;</div>
                            </td>
                        </tr>
                    </table>

                    <table cellpadding="0" cellspacing="0" border="0" width="90%" style="width: 90% !important; min-width: 90%; max-width: 90%; border-width: 1px; border-style: solid; border-color: #e8e8e8; border-bottom: none; border-left: none; border-right: none;">
                        <tr>
                            <td align="left" valign="top">
                            <div style="height: 15px; line-height: 15px; font-size: 13px;">&nbsp;</div>
                            </td>
                        </tr>
                    </table>

                    <table cellpadding="0" cellspacing="0" border="0" width="88%" style="width: 88% !important; min-width: 88%; max-width: 88%;">
                        <tr>
                            <td align="center" valign="top">
                            <div style="display: inline-block; vertical-align: top; width: 50px;">
                                <table cellpadding="0" cellspacing="0" border="0" width="100%" style="width: 100% !important; min-width: 100%; max-width: 100%;">
                                    <tr>
                                        <td align="center" valign="top">
                                        <div style="height: 13px; line-height: 13px; font-size: 11px;">&nbsp;</div>
                                        <div style="display: block; max-width: 50px;">
                                            <img src="https://sistema.emprotege.com/app/assets/mail/logo.jpg" alt="img" width="50" border="0" style="display: block; width: 50px;" />
                                        </div>
                                        </td>
                                    </tr>
                                </table>
                            </div><div class="mob_div" style="display: inline-block; vertical-align: top; width: 62%; min-width: 260px;">
                                <table cellpadding="0" cellspacing="0" border="0" width="100%" style="width: 100% !important; min-width: 100%; max-width: 100%;">
                                    <tr>
                                        <td width="18" style="width: 18px; max-width: 18px; min-width: 18px;">&nbsp;</td>
                                        <td class="mob_center" align="left" valign="top">
                                        <div style="height: 13px; line-height: 13px; font-size: 11px;">&nbsp;</div>
                                        <font face="Source Sans Pro, sans-serif" color="#000000" style="font-size: 19px; line-height: 23px; font-weight: 600;">
                                            <span style="font-family: Source Sans Pro, Arial, Tahoma, Geneva, sans-serif; color: #000000; font-size: 19px; line-height: 23px; font-weight: 600;">'.$bodyCompany.'</span>
                                        </font>
                                        <div style="height: 1px; line-height: 1px; font-size: 1px;">&nbsp;</div>
                                        <font face="Source Sans Pro, sans-serif" color="#7f7f7f" style="font-size: 19px; line-height: 23px;">
                                            <span style="font-family: Source Sans Pro, Arial, Tahoma, Geneva, sans-serif; color: #7f7f7f; font-size: 19px; line-height: 23px;">'.$bodySlogan.'</span>
                                        </font>
                                        </td>
                                        <td width="18" style="width: 18px; max-width: 18px; min-width: 18px;">&nbsp;</td>
                                    </tr>
                                </table>
                            </div><div style="display: inline-block; vertical-align: top; width: 177px;">
                                <table cellpadding="0" cellspacing="0" border="0" width="100%" style="width: 100% !important; min-width: 100%; max-width: 100%;">
                                    <tr>
                                        <td align="center" valign="top">
                                        <div style="height: 13px; line-height: 13px; font-size: 11px;">&nbsp;</div>
                                        <div style="display: block; max-width: 177px;">
                                            <img src="https://sistema.emprotege.com/app/assets/mail/logo_cybercode.png" alt="img" width="177" border="0" style="display: block; width: 177px; max-width: 50%;" />
                                        </div>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                            <div style="height: 30px; line-height: 30px; font-size: 28px;">&nbsp;</div>
                            </td>
                        </tr>
                    </table>

                    <table cellpadding="0" cellspacing="0" border="0" width="100%" style="width: 100% !important; min-width: 100%; max-width: 100%; background: #f3f3f3;">
                        <tr>
                            <td align="center" valign="top">
                            <div style="height: 34px; line-height: 34px; font-size: 32px;">&nbsp;</div>
                            <table cellpadding="0" cellspacing="0" border="0" width="88%" style="width: 88% !important; min-width: 88%; max-width: 88%;">
                                <tr>
                                    <td align="center" valign="top">
                                        <div style="height: 3px; line-height: 3px; font-size: 1px;">&nbsp;</div>
                                        <font face="Source Sans Pro, sans-serif" color="#1a1a1a" style="font-size: 17px; line-height: 20px;">
                                        <span style="font-family: Source Sans Pro, Arial, Tahoma, Geneva, sans-serif; color: #1a1a1a; font-size: 17px; line-height: 20px;"><a href="#" target="_blank" style="font-family: Source Sans Pro, Arial, Tahoma, Geneva, sans-serif; color: #1a1a1a; font-size: 17px; line-height: 20px; text-decoration: none;">'.$bodyEmail.'</a> &nbsp;&nbsp;|&nbsp;&nbsp; <a href="#" target="_blank" style="font-family: Source Sans Pro, Arial, Tahoma, Geneva, sans-serif; color: #1a1a1a; font-size: 17px; line-height: 20px; text-decoration: none;">'.$bodyTel.'</a></span>
                                        </font>
                                        <div style="height: 34px; line-height: 34px; font-size: 32px;">&nbsp;</div>
                                        <font face="Source Sans Pro, sans-serif" color="#868686" style="font-size: 9px; line-height: 20px;">
                                        <span style="font-family: Source Sans Pro, Arial, Tahoma, Geneva, sans-serif; color: #868686; font-size: 17px; line-height: 20px;">Copyright &copy; Cybercode Sistemas 2019</span>
                                        </font>
                                    </td>
                                </tr>
                            </table>
                            </td>
                        </tr>
                    </table>  

                </td>
                <td class="mob_pad" width="25" style="width: 25px; max-width: 25px; min-width: 25px;">&nbsp;</td>
                </tr>
            </table>
        </td>
    </tr>
    </table>
    </body>
    </html>

    ';

}

//sendMail("NOVO LAYOUT DE EMAIL", ["gustavohvs.dev@gmail.com"], "app@emprevencao.com.br", "EM Robot", "Testando novo layout de e-mail automático...", "EM Prevenção", "Rumo a excelência!", "central@emprevencao.com.br", "(31)3568-9084");

?>