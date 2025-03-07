<?php
session_start();
require 'db.php';

if (isset($_GET['register_no'], $_GET['semester'], $_GET['department'])) {
    $register_no = trim($_GET['register_no']);
    $semester = trim($_GET['semester']);
    $department = trim($_GET['department']);

    // Debugging: Log the received parameters
    error_log("Fetching grades for register_no: $register_no, semester: $semester, department: $department");

    // Fetch grades for the student
    $stmt = $mysqli->prepare("
        SELECT subject_id, grade 
        FROM student_grades 
        WHERE register_no = ? 
          AND semester = ? 
          AND TRIM(department) = ?
    ");
    if (!$stmt) die("Prepare failed: " . $mysqli->error);
    $stmt->bind_param('sss', $register_no, $semester, $department);
    $stmt->execute();
    $grades = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

    // Debugging: Log the fetched grades
    error_log("Fetched grades: " . print_r($grades, true));

    // Fetch profile image
    $stmt = $mysqli->prepare("SELECT profile_image FROM stud WHERE register_no = ?");
    if (!$stmt) die("Prepare failed: " . $mysqli->error);
    $stmt->bind_param('s', $register_no);
    $stmt->execute();
    $result = $stmt->get_result()->fetch_assoc();
    $profile_image = $result ? $result['profile_image'] : null;

    // Debugging: Log the fetched profile image
    error_log("Fetched profile_image: $profile_image");

    echo json_encode([
        'grades' => $grades,
        'profile_image' => $profile_image
    ]);
} else {
    echo json_encode(['error' => 'Missing parameters']);
}
?>