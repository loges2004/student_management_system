<?php
include("db.php");

// Check if the ID parameter is set
if (isset($_GET['id'])) {
    $subject_code= $_GET['id'];

    // Prepare the SQL query to delete the department
    $sql = "DELETE FROM subjects WHERE subject_code= ?";
    $stmt = $mysqli->prepare($sql);

    if ($stmt === false) {
        die("Prepare failed: " . $mysqli->error);
    }

    // Bind the department ID parameter
    $stmt->bind_param("s", $subject_code);

   
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
                        window.location.href = 'subject_manage.php';
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
                        window.location.href = 'subject_manage.php';
                    }
                });
            });
        </script>";
    }
}else {
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
                    window.location.href = 'subject_manage.php';
                }
            });
        });
    </script>";
}
$mysqli->close();
?>