<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer-6.1.7/src/Exception.php';
require 'PHPMailer-6.1.7/src/PHPMailer.php';
require 'PHPMailer-6.1.7/src/SMTP.php';

$fname = $lname = $email = $gender = $country = $subject = $comment = ""; // Defines all variables and put them empty
$errors = array();

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
    } catch (Exception $e) {
        echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }
}

function addToDatabase()
{
    global $id;
    global $fname;
    global $lname;
    global $gender;
    global $email;
    global $country;
    global $subject;
    global $comment;

    $id = uniqid();
    require('login.php');

    $servername = "mysql-calvin-jitnaree.alwaysdata.net";
    $username = $mysqlUser;
    $password = $mysqlPass;
    $dbname = "calvin-jitnaree_hackerspoulette";

    try {

        $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "INSERT INTO tickets (id, firstname, lastname, email, gender,country, subject, comment)
  VALUES ('" . $id . "','" . $fname . "', '" . $lname . "', '" . $email . "','" . $gender . "', '" . $country . "', '" . $subject . "', '" . $comment . "')";
        $conn->exec($sql);

        echo "<meta http-equiv='refresh'
        content='0; url=https://calvin-jitnaree.alwaysdata.net/hackersP/submitted.php'>";
    } catch (PDOException $e) {
        echo $sql . "<br>" . $e->getMessage();
    }

    $conn = null;
}

function isFormValidate()
{
    global $errors;
    if (count($errors) === 0) {
        return true;
    } else {
        return false;
    }
}

if ((isset($_POST["submit"]))) {

    if (isset($_POST['raison']) && !empty($_POST['raison'])) { // HONEY SPOT TRAP
        $errors['BotCatched'] = "Bot is trying to spam";
    }
    
    if (false === filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) { // FOR EACH DATA WE CHECK IF THE INPUT IS VALID IF NOT WE DEFINE AN ERROR.
        $errors['emailErr'] = "Email adress Invalid";
    } else {
        $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);   // IF VALID WE REDEFINE THE VARIABLE SANITIZED.
    }

    if (!preg_match("/^[A-zÀ-ÿ]+$/", $_POST['firstname']) or empty($_POST['firstname'])) {
        $errors['firstNameErr'] = "Only letters and white space allowed for firstname and can't be empty";
    } else {
        $fname = filter_var($_POST['firstname'], FILTER_SANITIZE_STRING);
    }

    if (!preg_match("/^[A-zÀ-ÿ]+$/", $_POST['lastname']) or empty($_POST['firstname'])) {
        $errors['lastNameErr'] = "Only letters and white space allowed for lastname and can't be empty";
    } else {
        $lname = filter_var($_POST['lastname'], FILTER_SANITIZE_STRING);
    }

    if (empty($_POST['gender'])) {
        $errors['genderErr'] = "Gender is required";
    } else {
        $gender = filter_var($_POST['gender'], FILTER_SANITIZE_STRING);
    }

    if (empty($_POST['country'])) {
        $errors['countryErr'] = "Country is not selected";
    } else {
        $country = filter_var($_POST['country'], FILTER_SANITIZE_STRING);
    }

    if (empty($_POST['subject'])) {
        $errors['subjectErr'] = "Subject in not selected";
    } else {
        $subject = filter_var($_POST['subject'], FILTER_SANITIZE_STRING);
    }

    if (strlen($_POST['comment']) < 30) {
        $errors['commentErr'] = "Comment is too short minimum 30 characters";
    } else {
        $comment = filter_var($_POST['comment'], FILTER_SANITIZE_STRING);
    }

    if (!isFormValidate()) { //  Check if form is validate
        echo "There are errors! Form is not well completed or not validated <br>";
        echo "there are " . count($errors) . " errors <br>";
        print_r($errors);
        exit; // Stop everything
    }

    addToDatabase(); // Add all the cleaned data to database
    sendMail(); // Send the mail with cleaned data
}
