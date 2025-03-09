<?php
include('db.php'); // Include database connection

if (isset($_GET['register_No'])) {
    $register_no= $_GET['register_No'];
    $query = "DELETE FROM stud WHERE register_no = $register_no";
    if ($mysqli->query($query)) {
        header('Location: staff_entry.php'); // Redirect back to staff management
    } else {
        echo "Error deleting record: " . $mysqli->error;
    }
}
?>