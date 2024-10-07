<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Password Reset</title>
    <!-- Bootstrap CSS -->
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <!-- SweetAlert CSS -->
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
    <style>
        .form-container {
            max-width: 500px;
            margin: 0 auto;
            padding: 30px;
            border: 1px solid #ddd;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        @media (max-width: 576px) {
            .form-container {
                padding: 15px;
            }
        }
    </style>
</head>
<body>
<div class="container mt-5">
    <div class="form-container">
        <h2 class="text-center">Password Reset</h2>

        <?php
        require 'db.php'; // Ensure this file correctly sets up $mysqli

        // Include the Composer autoloader
        require 'vendor/autoload.php';

        use PHPMailer\PHPMailer\PHPMailer;
        use PHPMailer\PHPMailer\Exception;

        if (isset($_POST['email'])) {
            $email = $_POST['email'];

            // Sanitize the input
            $email = htmlspecialchars($email);

            // Generate a reset token
            $token = bin2hex(random_bytes(32));
            $expires_at = date('Y-m-d H:i:s', strtotime('+24 hour')); // Token valid for 24 hours

            // Prepare and execute the SQL statement
            if ($stmt = $mysqli->prepare("UPDATE students SET token = ?, token_expires_at = ? WHERE email = ?")) {
                $stmt->bind_param('sss', $token, $expires_at, $email);
                $stmt->execute();

                if ($stmt->affected_rows > 0) {
                    // Send the reset email
                    $resetLink = "http://localhost/studentmanagementsystem/reset_password.php?token=" . urlencode($token) . "&email=" . urlencode($email);

                    $mail = new PHPMailer(true); // Enable exceptions
                    try {
                        // Server settings
                        $mail->isSMTP();
                        $mail->Host = 'smtp.gmail.com'; // Replace with your SMTP server
                        $mail->SMTPAuth = true;
                        $mail->Username = 'psnacet07@gmail.com'; // SMTP username
                        $mail->Password = 'bivs fdoz ywvw gzhg'; // SMTP password (use your own or a secure application-specific password)
                        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                        $mail->Port = 587;

                        // Recipients
                        $mail->setFrom('psnacet07@gmail.com', 'Password Reset');
                        $mail->addAddress($email);

                        // Content
                        $mail->isHTML(true);
                        $mail->Subject = 'Password Reset Request';
                        $mail->Body = 'Click on the following link to reset your password: <a href="' . $resetLink . '">' . $resetLink . '</a>';

                        $mail->send();
                        echo '<script>
                            Swal.fire({
                                title: "Success",
                                text: "Password reset link has been sent to your email.",
                                icon: "success",
                                confirmButtonText: "OK"
                            }).then(() => {
                                window.location.href = "login.php";
                            });
                        </script>';
                    } catch (Exception $e) {
                        echo '<script>
                            Swal.fire({
                                title: "Error",
                                text: "Message could not be sent. Mailer Error: ' . $mail->ErrorInfo . '",
                                icon: "error",
                                confirmButtonText: "OK"
                            });
                        </script>';
                    }
                } else {
                    echo '<script>
                        Swal.fire({
                            title: "Error",
                            text: "No user found with that email address.",
                            icon: "error",
                            confirmButtonText: "OK"
                        });
                    </script>';
                }

                $stmt->close();
            } else {
                echo '<script>
                    Swal.fire({
                        title: "Error",
                        text: "Error preparing the SQL statement: ' . $mysqli->error . '",
                        icon: "error",
                        confirmButtonText: "OK"
                    });
                </script>';
            }
        }

        $mysqli->close();
        ?>

        <form action="forgot_password.php" method="POST">
            <div class="form-group">
                <label for="email">Enter your email:</label>
                <input type="email" class="form-control" id="email" name="email" placeholder="Enter your email" required>
            </div>
            <button type="submit" class="btn btn-primary btn-block">Send Reset Link</button>
        </form>
    </div>
</div>

<!-- Bootstrap JS and dependencies -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<!-- SweetAlert JS -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</body>
</html>
