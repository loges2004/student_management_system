<?php
require 'db.php'; // Import database configuration

if (isset($_GET['email']) && isset($_GET['token'])) {
    $email = $_GET['email'];
    $token = $_GET['token'];

    // Prepare the SQL statement to check if the user exists and the token is correct
    $stmt = $mysqli->prepare("SELECT * FROM students WHERE email = ? AND token = ? AND status = 'unverified'");
    if ($stmt) {
        $stmt->bind_param('ss', $email, $token);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            // Prepare the SQL statement to update the user's status to verified
            $updateStmt = $mysqli->prepare("UPDATE students SET status = 'verified', token = '' WHERE email = ?");
            if ($updateStmt) {
                $updateStmt->bind_param('s', $email);
                $updateStmt->execute();
                $updateStmt->close();

                echo "<script>
                    Swal.fire({
                        icon: 'success',
                        title: 'Email verified!',
                        text: 'You can now log in.',
                        position: 'top',
                        showConfirmButton: true
                    }).then(() => {
                        window.location.href = 'login2.php';
                    });
                </script>";
            } else {
                echo "<script>
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Failed to update user status.',
                        position: 'top',
                        showConfirmButton: true
                    }).then(() => {
                        window.location.href = 'register.php';
                    });
                </script>";
            }
        } else {
            echo "<script>
                Swal.fire({
                    icon: 'error',
                    title: 'Invalid verification link',
                    text: 'Please try registering again.',
                    position: 'top',
                    showConfirmButton: true
                }).then(() => {
                    window.location.href = 'register.php';
                });
            </script>";
        }

        $stmt->close();
    } else {
        echo "<script>
            Swal.fire({
                icon: 'error',
                title: 'Database Error',
                text: 'Failed to prepare statement.',
                position: 'top',
                showConfirmButton: true
            }).then(() => {
                window.location.href = 'register.php';
            });
        </script>";
    }
} else {
    echo "<script>
        Swal.fire({
            icon: 'error',
            title: 'Invalid access',
            text: 'Please use the link sent to your email.',
            position: 'top',
            showConfirmButton: true
        }).then(() => {
            window.location.href = 'register.php';
        });
    </script>";
}

// Close the database connection
$mysqli->close();
?>
