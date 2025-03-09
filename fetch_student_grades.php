<?php
session_start();
require 'db.php';

if (isset($_GET['register_no']) && isset($_GET['semester']) && isset($_GET['department']) && isset($_GET['regulation'])) {
    $register_no = $_GET['register_no'];
    $semester = $_GET['semester'];
    $department = $_GET['department'];
    $regulation = $_GET['regulation'];

    // Fetch grades for the student
    $stmt = $mysqli->prepare("
        SELECT subject_id, grade 
        FROM student_grades 
        WHERE register_no = ? 
          AND semester = ? 
          AND department = ?
          AND regulation = ?
    ");
    if ($stmt === false) die('MySQL prepare error: ' . $mysqli->error);
    $stmt->bind_param('ssss', $register_no, $semester, $department, $regulation);
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
        error_log("No grades found for register_no: $register_no, semester: $semester, department: $department, regulation: $regulation");
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