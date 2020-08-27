<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'countries.php';
require 'PHPMailer-6.1.7/src/Exception.php';
require 'PHPMailer-6.1.7/src/PHPMailer.php';
require 'PHPMailer-6.1.7/src/SMTP.php';

$email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
$fname = filter_var($_POST['firstname'], FILTER_SANITIZE_STRING);
$lname = filter_var($_POST['lastname'], FILTER_SANITIZE_STRING);
$gender = filter_var($_POST['gender'], FILTER_SANITIZE_STRING);
$country = filter_var($_POST['country'], FILTER_SANITIZE_STRING);
$subject = filter_var($_POST['subject'], FILTER_SANITIZE_STRING);
$comment = filter_var($_POST['comment'], FILTER_SANITIZE_STRING);
$errors = array();
$id = uniqid();

function sendMail()
{
    global $id;
    global $fname;
    global $lname;
    global $gender;
    global $email;
    global $country;
    global $subject;
    global $comment;

    require 'login.php';

    $mail = new PHPMailer(true);
    try {
        //Server settings
        $mail->SMTPDebug = 0;                      // Enable verbose debug output
        $mail->isSMTP();                                            // Send using SMTP
        $mail->Host       = 'smtp.gmail.com';                    // Set the SMTP server to send through
        $mail->SMTPAuth   = true;                                   // Enable SMTP authentication
        $mail->Username   = $smtpUser;                     // SMTP username
        $mail->Password   = $smtpPass;                               // SMTP password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;         // Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` encouraged
        $mail->Port       = 587;                                    // TCP port to connect to, use 465 for `PHPMailer::ENCRYPTION_SMTPS` above

        //Recipients
        $mail->setFrom('hackerspoulette@gmail.com', 'Hackers Poulette | Support');
        $mail->addAddress($email);     // Add a recipient


        // Content
        $mail->isHTML(true);                                  // Set email format to HTML
        $mail->Subject = 'Thank you for contacting us';
        $mail->Body    = "<h3>Hello <strong>" . $fname . " " . $lname . ",</strong></h3> This email is to confirm you that our Team received your message. <br>We will reply within 24 yours. <br><br>----> your ticket ID is: <strong>[#" . $id . "]</strong> <br> Thank you for your patience, <br> <br> <strong>Hackers Poulette's Team.</strong>";
        $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

        $mail->send();
        //echo 'Message has been sent';
    } catch (Exception $e) {
        echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }
}

function process()
{
    global $id;
    global $fname;
    global $lname;
    global $gender;
    global $email;
    global $country;
    global $subject;
    global $comment;
    require ('login.php');

    $servername = "mysql-calvin-jitnaree.alwaysdata.net";
    $username = $mysqlUser;
    $password = $mysqlPass;
    $dbname = "calvin-jitnaree_hackerspoulette";

    try {

        $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
        // set the PDO error mode to exception
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "INSERT INTO tickets (id, firstname, lastname, email, gender,country, subject, comment)
  VALUES ('" . $id . "','" . $fname . "', '" . $lname . "', '" . $email . "','" . $gender . "', '" . $country . "', '" . $subject . "', '" . $comment . "')";
        // use exec() because no results are returned
        $conn->exec($sql);
        sendMail();
        echo "<meta http-equiv='refresh'
        content='0; url=https://calvin-jitnaree.alwaysdata.net/hackersP/submitted.php'>";

    } catch (PDOException $e) {
        echo $sql . "<br>" . $e->getMessage();
    }

    $conn = null;
}


if (isset($_POST['raison']) && !empty($_POST['raison'])) { // HoneySpot Trap
    $errors['email'] = "Bot catched in hotneypot trap";
}
if (false === filter_var($email, FILTER_VALIDATE_EMAIL)) { // Check if the email is a valid one
    $errors['email'] = "Email adress Invalid";
}

if (empty($message) && empty($fname) && empty($lname) && empty($email)) { //  Check if form is completed
    $errors['incomplete'] = "Form is not completed";
}

if (count($errors) > 0) { // Check if all checkpoint passed
    echo "There are mistakes!";
    print_r($errors);
    exit; // Stop everything
}

process(); // All fine, -> PROCESS
