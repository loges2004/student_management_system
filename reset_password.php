<?php
require 'db.php'; // Ensure this file correctly sets up $mysqli

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Handle form submission to update the password
    $token = htmlspecialchars($_POST['token'], ENT_QUOTES, 'UTF-8');
    $email = htmlspecialchars($_POST['email'], ENT_QUOTES, 'UTF-8');
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    // Validate the new passwords
    if ($new_password === $confirm_password) {
        // Hash the new password
        $hashed_password = password_hash($new_password, PASSWORD_BCRYPT);

        // Update the password in the database
        if ($stmt = $mysqli->prepare("UPDATE students SET password = ?, token = NULL, token_expires_at = NULL WHERE email = ? AND token = ?")) {
            $stmt->bind_param('sss', $hashed_password, $email, $token);
            if ($stmt->execute()) {
                echo '<script>
                        Swal.fire({
                            title: "Success",
                            text: "Password has been reset successfully.",
                            icon: "success",
                            confirmButtonText: "OK"
                        }).then(() => {
                            window.location.href = "login22.php";
                        });
                      </script>';
            } else {
                echo '<script>
                        Swal.fire({
                            title: "Error",
                            text: "Error updating password.",
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
                        text: "Error preparing the SQL statement.",
                        icon: "error",
                        confirmButtonText: "OK"
                    });
                  </script>';
        }
    } else {
        echo '<script>
                Swal.fire({
                    title: "Error",
                    text: "Passwords do not match.",
                    icon: "error",
                    confirmButtonText: "OK"
                });
              </script>';
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['token']) && isset($_GET['email'])) {
    $token = htmlspecialchars($_GET['token'], ENT_QUOTES, 'UTF-8');
    $email = htmlspecialchars($_GET['email'], ENT_QUOTES, 'UTF-8');

    // Prepare and execute the query to check token validity
    if ($stmt = $mysqli->prepare("SELECT * FROM students WHERE email = ? AND token = ? AND token_expires_at > NOW()")) {
        $stmt->bind_param('ss', $email, $token);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            // Token is valid, display the reset password form
            echo '
            <!DOCTYPE html>
            <html lang="en">
            <head>
                <meta charset="UTF-8">
                <meta name="viewport" content="width=device-width, initial-scale=1.0">
                <title>Reset Password</title>
                <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
                <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
                <style>
                    body {
                        background-color: #f8f9fa;
                        display: flex;
                        justify-content: center;
                        align-items: center;
                        height: 100vh;
                        margin: 0;
                    }
                    .container {
                        max-width: 500px;
                        background: white;
                        padding: 20px;
                        border-radius: 8px;
                        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
                    }
                    .form-group {
                        margin-bottom: 1rem;
                    }
                    .form-group .input-group {
                        position: relative;
                    }
                    .form-group .input-group .input-group-append .input-group-text {
                        cursor: pointer;
                    }
                </style>
            </head>
            <body>
            <div class="container">
                <h2 class="text-center">Reset Password</h2>
                <form action="reset_password.php" method="post">
                    <input type="hidden" name="token" value="' . htmlspecialchars($token, ENT_QUOTES, 'UTF-8') . '">
                    <input type="hidden" name="email" value="' . htmlspecialchars($email, ENT_QUOTES, 'UTF-8') . '">
                    <div class="form-group">
                        <label for="new_password">New Password</label>
                        <div class="input-group">
                            <input type="password" id="new_password" name="new_password" class="form-control" placeholder="Enter new password" required>
                            <div class="input-group-append">
                                <span class="input-group-text" id="togglePassword"><i class="fas fa-eye"></i></span>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="confirm_password">Confirm Password</label>
                        <div class="input-group">
                            <input type="password" id="confirm_password" name="confirm_password" class="form-control" placeholder="Confirm new password" required>
                            <div class="input-group-append">
                                <span class="input-group-text" id="toggleConfirmPassword"><i class="fas fa-eye"></i></span>
                            </div>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary btn-block">Reset Password</button>
                </form>
            </div>
            <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
            <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
            <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
            <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
            <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
            <script>
                document.getElementById("togglePassword").addEventListener("click", function () {
                    const passwordField = document.getElementById("new_password");
                    const icon = this.querySelector("i");
                    if (passwordField.type === "password") {
                        passwordField.type = "text";
                        icon.classList.remove("fa-eye");
                        icon.classList.add("fa-eye-slash");
                    } else {
                        passwordField.type = "password";
                        icon.classList.remove("fa-eye-slash");
                        icon.classList.add("fa-eye");
                    }
                });
                
                document.getElementById("toggleConfirmPassword").addEventListener("click", function () {
                    const confirmPasswordField = document.getElementById("confirm_password");
                    const icon = this.querySelector("i");
                    if (confirmPasswordField.type === "password") {
                        confirmPasswordField.type = "text";
                        icon.classList.remove("fa-eye");
                        icon.classList.add("fa-eye-slash");
                    } else {
                        confirmPasswordField.type = "password";
                        icon.classList.remove("fa-eye-slash");
                        icon.classList.add("fa-eye");
                    }
                });
            </script>
            </body>
            </html>';
        } else {
            echo '<div class="alert alert-danger" role="alert">Invalid or expired token.</div>';
        }

        $stmt->close();
    } else {
        echo '<div class="alert alert-danger" role="alert">Error preparing the SQL statement: ' . $mysqli->error . '</div>';
    }
} else {
    echo '<div class="alert alert-danger" role="alert">Invalid access.</div>';
}

$mysqli->close();
?>
