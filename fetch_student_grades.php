<?php
session_start();
require 'db.php';

if (isset($_GET['register_no']) && isset($_GET['semester']) && isset($_GET['department'])) {
    $register_no = $_GET['register_no'];
    $semester = $_GET['semester'];
    $department = $_GET['department'];

    // Fetch grades for the student
    $stmt = $mysqli->prepare("
        SELECT subject_id, grade 
        FROM student_grades 
        WHERE register_no = ? 
          AND semester = ? 
          AND department = ?
    ");
    if ($stmt === false) die('MySQL prepare error: ' . $mysqli->error);
    $stmt->bind_param('sss', $register_no, $semester, $department);
    $stmt->execute();
    $grades = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);


    
    // Fetch profile image
    $stmt = $mysqli->prepare("SELECT profile_image FROM stud WHERE register_no = ?");
    if ($stmt === false) die('MySQL prepare error: ' . $mysqli->error);
    $stmt->bind_param('s', $register_no);
    $stmt->execute();
    $profile_image = $stmt->get_result()->fetch_assoc()['profile_image'];

    // Debugging: Check if data is fetched correctly
    if (empty($grades)) {
        error_log("No grades found for register_no: $register_no, semester: $semester, department: $department");
    }
    if (empty($profile_image)) {
        error_log("No profile image found for register_no: $register_no");
    }

    // Prepare response
    $response = [
        'grades' => $grades,
        'profile_image' => $profile_image
    ];

    echo json_encode($response);
} else {
    echo json_encode(['error' => 'Invalid request']);
}
?>