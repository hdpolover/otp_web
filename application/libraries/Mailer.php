<?php defined('BASEPATH') or exit('No direct script access allowed');

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class Mailer
{

  public function __construct()
  {
    log_message('Debug', 'PHPMailer class is loaded.');
    $this->_ci = &get_instance();
    $this->_ci->load->database();
  }

  public function send($data)
  {
    // Include PHPMailer library files
    require_once APPPATH . 'third_party/PHPMailer/Exception.php';
    require_once APPPATH . 'third_party/PHPMailer/PHPMailer.php';
    require_once APPPATH . 'third_party/PHPMailer/SMTP.php';

    $mail = new PHPMailer(true);
    
    // SMTP configuration
    $mail->isSMTP();

    $mail->SMTPOptions = array(
      'ssl' => array(
        'verify_peer' => false,
        'verify_peer_name' => false,
        'allow_self_signed' => true
      )
    );
      
    // $mail->SMTPDebug      = 3;
    $mail->SMTPAuth = true;
    $mail->SMTPKeepAlive = true;
    $mail->SMTPSecure = "tls";
    $mail->Port = 587;
    $mail->Host = "smtp.gmail.com";
    $mail->Username = "ngodingin.indonesia@gmail.com";
    $mail->Password = "zxpgwtxyazhyrhoj";

    $mail->setFrom("support@webotpku.xyz", "support@webotpku.xyz");
    $mail->addReplyTo("support@webotpku.xyz", "support@webotpku.xyz");
      
      // Add a recipient
    $mail->addAddress($data['to']);
      
      // Email subject
    $mail->Subject = $data['subject'];
      
      // Set email format to HTML
    $mail->isHTML(true);
      // Email body content
    $mail->Body = $data['message'];
      
      // Send email
    if (!$mail->send()) {
      echo 'Message could not be sent. <br>';
      echo 'Mailer Error: ' . $mail->ErrorInfo;
      echo '<br>Contact ADMIN ';
      die();
      return false;
    } else {
      return true;
    }
  }

} ?>