<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Simple .env Loader
function loadEnv($path)
{
    if (!file_exists($path)) {
        die(".env file not found");
    }

    $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

    foreach ($lines as $line) {
        if (strpos(trim($line), '#') === 0) continue;

        list($name, $value) = explode('=', $line, 2);
        $_ENV[$name] = trim($value);
    }
}

loadEnv(__DIR__ . '/.env');
require __DIR__ . '/vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// require __DIR__ . '/PHPMailer/src/Exception.php';
// require __DIR__ . '/PHPMailer/src/PHPMailer.php';
// require __DIR__ . '/PHPMailer/src/SMTP.php';

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
        $mail->Host       = $_ENV['MAIL_HOST'];
        $mail->SMTPAuth   = true;
        $mail->Username   = $_ENV['MAIL_USERNAME'];
        $mail->Password   = $_ENV['MAIL_PASSWORD'];
        $mail->SMTPSecure = $_ENV['MAIL_ENCRYPTION'];
        $mail->Port       = $_ENV['MAIL_PORT'];

        $mail->setFrom($_ENV['MAIL_FROM'], $_ENV['MAIL_FROM_NAME']);
        $mail->addAddress('mahimaurya2004@gmail.com'); // Keeping this hardcoded as requested

        $mail->isHTML(true);
        $mail->Subject = $_ENV['MAIL_SUBJECT'];
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

