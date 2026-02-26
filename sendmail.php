<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require __DIR__ . '/PHPMailer/src/Exception.php';
require __DIR__ . '/PHPMailer/src/PHPMailer.php';
require __DIR__ . '/PHPMailer/src/SMTP.php';

if (isset($_POST['send'])) {

    $fname  = $_POST['firstname'];
    $lname  = $_POST['lastname'];
    $rno    = $_POST['rollno'];
    $class  = $_POST['class'];
    $email  = $_POST['email'];

    $mail = new PHPMailer(true);

    try {
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'maurya.mahima2007@gmail.com';
        $mail->Password = 'your_app_password'; 
        $mail->SMTPSecure = 'tls';
        $mail->Port = 587;

        $mail->setFrom('maurya.mahima2007@gmail.com', 'PHP Emailer');
        $mail->addAddress('mahimaurya2004@gmail.com');

        $mail->isHTML(true);
        $mail->Subject = 'Student Registration Form';
        $mail->Body = "
            <b>First Name:</b> $fname <br>
            <b>Last Name:</b> $lname <br>
            <b>Roll No:</b> $rno <br>
            <b>Class:</b> $class <br>
            <b>Email:</b> $email <br>
        ";

        $mail->send();
        echo "Email sent successfully!";
    } catch (Exception $e) {
        echo "Email failed: " . $mail->ErrorInfo;
    }
} else {
    echo "Form not submitted properly.";
}
?>