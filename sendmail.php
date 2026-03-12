<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require __DIR__ . '/vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();

require __DIR__ . '/PHPMailer/src/Exception.php';
require __DIR__ . '/PHPMailer/src/PHPMailer.php';
require __DIR__ . '/PHPMailer/src/SMTP.php';

$conn = new mysqli(
    $_ENV['DB_HOST'],
    $_ENV['DB_USER'],
    $_ENV['DB_PASS'],
    $_ENV['DB_NAME']
);

if($conn->connect_error){
    die("Connection Failed: ". $conn->connect_error);
}

if (isset($_POST['send'])) {

    $fname  = $_POST['firstname'];
    $lname  = $_POST['lastname'];
    $rno    = $_POST['rollno'];
    $class  = $_POST['class'];
    $email  = $_POST['email'];

    $sql="INSERT INTO students(firstname,lastname,rollno,class,email)VALUES ('$fname','$lname','$rno','$class','$email')";
    $conn->query($sql);

    $mail = new PHPMailer(true);

    try {
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;

        $mail->Username = $_ENV['MAIL_USERNAME'];
        $mail->Password = $_ENV['MAIL_PASSWORD']; 

        $mail->SMTPSecure = 'tls';
        $mail->Port = 587;

        $mail->setFrom($_ENV['MAIL_USERNAME'], 'PHP Emailer');
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