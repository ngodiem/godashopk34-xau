<?php 
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;
class EmailService {
    protected $error;
    function send($to, $subject, $content) {
        $mail = new PHPMailer(true);

        $mail->CharSet = 'UTF-8'; //fix tiếng việt
    
        try {
        //Server settings
        $mail->SMTPDebug = SMTP::DEBUG_OFF;                      //không có lổi
        $mail->isSMTP();                                            //Send using SMTP
        $mail->Host       = SMTP_HOST;                     //Xài của gmail
        $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
        $mail->Username   = SMTP_USERNAME;                     //gmail 
        $mail->Password   = SMTP_SECRET;                               //SMTP password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption
        $mail->Port       = 465;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`
    
        //Recipients
        $mail->setFrom(SMTP_USERNAME ); // xác thực gmail
        $mail->addAddress($to );     //gửi tới ai đó
       
    
        
        //Content
        $mail->isHTML(true);                                  //Set email format to HTML
        $mail->Subject = $subject;
        $mail->Body    = $content;
        
    
        $mail->send();
        return true;
    } catch (Exception $e) {
      $this->error = "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
      return false;
    }
    }
    function getError() {
        return $this->error;
    }
}
 ?>