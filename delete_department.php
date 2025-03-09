<?php
include("db.php");

// Check if the ID parameter is set
if (isset($_GET['id'])) {
    $department_id = $_GET['id'];

    // Prepare the SQL query to delete the department
    $sql = "DELETE FROM departments WHERE department_id = ?";
    $stmt = $mysqli->prepare($sql);

    if ($stmt === false) {
        die("Prepare failed: " . $mysqli->error);
    }

    // Bind the department ID parameter
    $stmt->bind_param("s", $department_id);

   
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
                        window.location.href = 'department_manage.php';
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
                        window.location.href = 'department_manage.php';
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
                    window.location.href = 'department_manage.php';
                }
            });
        });
    </script>";
}
$mysqli->close();
?>