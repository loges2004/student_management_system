<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
    <style>
        /* Adjust the size and position of the SweetAlert dialog */
        .swal2-popup {
            width: 400px; /* Set the width of the dialog */
        }
    </style>
</head>
<body>
<?php
require 'vendor/autoload.php'; // Make sure to include PHPMailer
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
$hostname = "localhost";
$username = "root";
$password = "";
$databasename = "lk";
$port = 4306;
$mysqli= mysqli_connect($hostname,$username,$password,$databasename,$port);
if(!$mysqli){
    echo ('connect error: '.mysqli_connect_error());
}
else{
    echo 'connection succes';
}
if (isset($_POST['submit'])) {
    $firstname = mysqli_real_escape_string($mysqli, $_POST['firstname']);
    $lastname = mysqli_real_escape_string($mysqli, $_POST['lastname']);
    $selectusertype = mysqli_real_escape_string($mysqli, $_POST['selectusertype']);
    $email = mysqli_real_escape_string($mysqli, $_POST['email']);
    $password = mysqli_real_escape_string($mysqli, $_POST['password']);

    // Server-side validation for college email domain
    $collegeDomain = "@psnacet.edu.in";
    if (substr($email, -strlen($collegeDomain)) !== $collegeDomain) {
        echo "<script>Swal.fire({
                icon: 'error',
                title: 'Invalid email domain',
                text: 'Please use your college email (@psnacet.edu.in)',
                position: 'top',
                showConfirmButton: true
            }).then(() => {
                window.location.href = 'register.php';
            });</script>";
        exit;
    }

    // Hash the password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Generate a verification token
    $token = bin2hex(random_bytes(50));

    // Insert user into the database with 'unverified' status
    $result = mysqli_query($mysqli, "INSERT INTO students (firstname, lastname, selectusertype, email, password, token, status) VALUES ('$firstname', '$lastname', '$selectusertype', '$email', '$hashed_password', '$token', 'unverified')");

    if ($result) {
        // Send verification email
        $mail = new PHPMailer(true); // Create an instance of PHPMailer

        try { 
            // Server settings
            $mail->isSMTP(); // Set mailer to use SMTP
            $mail->Host       = 'smtp.gmail.com'; // Set the SMTP server (update this)
            $mail->SMTPAuth   = true; // Enable SMTP authentication
            $mail->Username   = 'psnacet07@gmail.com'; // SMTP username (your email)
            $mail->Password   = 'bivs fdoz ywvw gzhg'; // SMTP password (use your app password or email password)
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; // Enable TLS encryption
            $mail->Port       = 587; // TCP port to connect to

            // Recipients
            $mail->setFrom('psnacet07@gmail.com', 'PSNA Registration'); // Sender's email and name
            $mail->addAddress($email); // Add a recipient

            // Content
            $mail->isHTML(true); // Set email format to HTML
            $mail->Subject = 'Verify your email address';
            $mail->Body = "Hi $firstname,<br>Please click the link below to verify your email address:<br><a href='http://localhost/studentmanagementsystem/verify.php?email=$email&token=$token'>Verify Email</a>";

            $mail->send(); // Send the email
            echo "<script>
                Swal.fire({
                    icon: 'success',
                    title: 'Registration successful!',
                    text: 'Please check your email to verify your account.',
                    position: 'top',
                    showConfirmButton: true
                }).then(() => {
                    window.location.href = 'login22.php';
                });
            </script>";
        } catch (Exception $e) {
            echo "<script>
                Swal.fire({
                    icon: 'error',
                    title: 'Email could not be sent.',
                    text: 'Mailer Error: {$mail->ErrorInfo}',
                    position: 'top',
                    showConfirmButton: true
                });
            </script>";
        }
    } else {
        echo "<script>
            Swal.fire({
                icon: 'error',
                title: 'Registration failed',
                text: 'Please try again later',
                position: 'top',
                showConfirmButton: true
            }).then(() => {
                window.location.href = 'register.php';
            });
        </script>";
    }
}
?>
</body>
</html>