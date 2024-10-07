<?php
session_start();
require 'db.php';

if (isset($_POST['register_no'])) {
    $register_no = $_POST['register_no'];

    // Fetch student details including profile image
    $stmt = $mysqli->prepare("SELECT student_name, department, profile_image FROM stud WHERE register_no = ?");
    $stmt->bind_param('s', $register_no);
    $stmt->execute();
    $result = $stmt->get_result();
    $student = $result->fetch_assoc();

    echo json_encode($student ? $student : null);
} else {
    echo json_encode(null);
}
?>
