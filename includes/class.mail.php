<?php
  require('constants.php');

  class Notification {
    private $uid;
    private $type;
    private $message;
    private $timestamp;
    private $recipient;
    private $allowedEmails;
    private $email;
    private $name;
    private $mysqli;

    function __construct($uid, $type, $message, $timestamp){
      $this->uid = $uid;
      $this->type = $type;
      $this->message = $message;
      $this->timestamp = $timestamp;
      $this->recipient = "";
      $this->allowedEmails = "";
      $name = "";

      $email = new PHPMailer();
      $email->isSMTP();
      $email->CharSet = 'utf8';
      $email->Host = INVITE_HOST;
  		$email->SMTPAuth = true;
  		$email->SMTPDebug = 1;
  		$email->Port = INVITE_PORT;
  		$email->Username = INVITE_MAIL;
  		$email->Password = INVITE_PASSWORD;
  		$email->SMTPSecure = 'tls';
      $email->setFrom(INVITE_MAIL, INVITE_NAME);
      $email->isHTML(true);

      $this->mysqi = new mysqli(HOST, USER, PASSWORD, DATABASE);
    }

    function getUserData(){
      $query = "SELECT person.name, login.email, person.allowedEmails FROM person INNER JOIN login ON login.uid = person.uid WHERE person.uid = ?";
      if($stmt = $mysqli->prepare($query)){
        $stmt->bind_param('s', $this->uid);
        $stmt->bind_result($name, $email, $accepts);
        $stmt->execute();
        $stmt->store_result();
        $this->recipient = $email;
        $this->name = $name;
        $this->allowedEmails = ($accepts === 1 ? true : false);
      }
    }
    function getEmailBody(){
      $this->email->Subject = "Änderungen an deinem Profil: {$this->message}";

      // Mail-Daten
      $invite_name = INVITE_NAME;
      $linkData = [
        "msg" => base64_encode($this->message),
        "invite_mail" => base64_encode(INVITE_MAIL),
        "invite_name" => base64_encode(INVITE_NAME),
        "name" => base64_encode($this->name)
      ];

      $this->email->body = require_once('body.mail.php');
      $this->email->AltBody = require_once('alt-body.mail.php');
    }


  }
 ?>
