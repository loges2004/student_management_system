
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
</head>
<body>
    
<?php
require 'db.php';
session_start();

if (isset($_POST['email']) && isset($_POST['password'])) {
    $email = mysqli_real_escape_string($mysqli, $_POST['email']);
    $password = mysqli_real_escape_string($mysqli, $_POST['password']);

    if (empty($email) || empty($password)) {
        echo '<script>
                Swal.fire({
                    icon: "error",
                    title: "Please enter both email and password",
                    position: "top",
                    showConfirmButton: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = "login22.php";
                    }
                });
             </script>';
    } else {
        // Retrieve user from database based on email
        $sql = "SELECT * FROM students WHERE email='$email'";
        $result = mysqli_query($mysqli, $sql);

        if ($result && mysqli_num_rows($result) > 0) {
            $row = mysqli_fetch_assoc($result);

            // Check if the email is verified
            if ($row['status'] !== 'verified') {
                echo '<script>
                        Swal.fire({
                            icon: "error",
                            title: "Email not verified",
                            text: "Please verify your email before logging in.",
                            position: "top",
                            showConfirmButton: true
                        }).then((result) => {
                            if (result.isConfirmed) {
                                window.location.href = "login22.php";
                            }
                        });
                     </script>';
            } else {
                // Verify hashed password
                if (password_verify($password, $row['password'])) {
                    $_SESSION['email'] = $row['email'];
                    $_SESSION['id'] = $row['id'];
                    $_SESSION['selectusertype'] = $row['selectusertype'];  // Set the user type
                
                    // Redirect based on user type
                    if ($row['selectusertype'] === 'staff') {
                        header("Location: staff_dashboard.php");
                        exit();
                    } elseif ($row['selectusertype'] === 'student') {
                        header("Location: student_cgpa.php");
                        exit();
                    }
                }
                else {
                    echo '<script>
                            Swal.fire({
                                icon: "error",
                                title: "Incorrect password",
                                position: "top",
                                showConfirmButton: true
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    window.location.href = "login22.php";
                                }
                            });
                         </script>';
                }
            }
        } else {
            echo '<script>
                    Swal.fire({
                        icon: "error",
                        text: "The email address you entered is not registered. Please register and verify your email.",                        position: "top",
                        showConfirmButton: true
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.location.href = "login22.php";
                        }
                    });
                 </script>';
        }
    }
}
?>
</body>
</html>
