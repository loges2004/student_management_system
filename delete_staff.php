<?php
include('db.php'); // Include database connection

if (isset($_GET['id'])) { // Use 'id' instead of 'staff_id'
    $staff_id = $_GET['id'];

    // Use prepared statements to prevent SQL injection
    $query = "DELETE FROM staff WHERE staff_id = ?";
    $stmt = $mysqli->prepare($query);

    if (!$stmt) {
        die("SQL Error: " . $mysqli->error);
    }

    $stmt->bind_param("s", $staff_id); // Bind the parameter

    if ($stmt->execute()) {
        echo "
        <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    title: 'Deleted!',
                    text: 'Record has been deleted successfully.',
                    icon: 'success',
                    confirmButtonText: 'OK'
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = 'staff_entry.php';
                    }
                });
            });
        </script>";
    } else {
        echo "
        <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    title: 'Error!',
                    text: 'Error deleting record: " . addslashes($stmt->error) . "',
                    icon: 'error',
                    confirmButtonText: 'OK'
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = 'staff_entry.php';
                    }
                });
            });
        </script>";
    }

    $stmt->close();
} else {
    echo "
    <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            Swal.fire({
                title: 'Error!',
                text: 'Invalid request.',
                icon: 'error',
                confirmButtonText: 'OK'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = 'staff_entry.php';
                }
            });
        });
    </script>";
}
?>