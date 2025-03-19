<?php
session_start();
include 'db.php';

header('Content-Type: application/json');

// Check session variables
if (!isset($_SESSION['staff_id'], $_SESSION['staff_name'], $_SESSION['year'], $_SESSION['semester'], $_SESSION['department'], $_SESSION['test_type'], $_SESSION['testmark'], $_SESSION['subject_name'], $_SESSION['subject_code'])) {
    echo json_encode(['status' => 'error', 'message' => 'Error: Missing session data.']);
    exit();
}

// Fetch session variables
$staff_id = $_SESSION['staff_id'];
$regulation = isset($_SESSION['regulation']) ? $_SESSION['regulation'] : '';
$staffname = strtoupper($_SESSION['staff_name']);
$year = $_SESSION['year'];
$semester = $_SESSION['semester'];
$department = strtoupper($_SESSION['department']);
$section = strtoupper($_SESSION['section']);
$test_type = strtoupper($_SESSION['test_type']);
$testmark = $_SESSION['testmark'];
$subject_name = strtoupper($_SESSION['subject_name']);
$subject_code = strtoupper($_SESSION['subject_code']);

// Check for existing record
$query = "SELECT id FROM test_results WHERE staff_id = ? AND year = ? AND semester = ? AND department = ? AND section = ? AND test_type = ? AND subject_code = ?";
$stmt = $mysqli->prepare($query);
if (!$stmt) {
    echo json_encode(['status' => 'error', 'message' => 'Error preparing statement: ' . $mysqli->error]);
    exit();
}

$stmt->bind_param("sssssss", $staff_id, $year, $semester, $department, $section, $test_type, $subject_code);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    // Update existing record
    $row = $result->fetch_assoc();
    $test_id = $row['id'];
    $update_query = "UPDATE test_results SET staffname = ?, regulation = ?, testmark = ?, subject_name = ? WHERE id = ?";
    $update_stmt = $mysqli->prepare($update_query);
    if (!$update_stmt) {
        echo json_encode(['status' => 'error', 'message' => 'Error preparing update statement: ' . $mysqli->error]);
        exit();
    }
    $update_stmt->bind_param("ssisi", $staffname, $regulation, $testmark, $subject_name, $test_id);
    if (!$update_stmt->execute()) {
        echo json_encode(['status' => 'error', 'message' => 'Error updating test_results: ' . $update_stmt->error]);
        exit();
    }
    $update_stmt->close();
} else {
    // Insert new record
    $insert_query = "INSERT INTO test_results (staff_id, staffname, regulation, year, semester, department, section, test_type, testmark, subject_name, subject_code) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $insert_stmt = $mysqli->prepare($insert_query);
    if (!$insert_stmt) {
        echo json_encode(['status' => 'error', 'message' => 'Error preparing insert statement: ' . $mysqli->error]);
        exit();
    }
    $insert_stmt->bind_param("ssssssssiss", $staff_id, $staffname, $regulation, $year, $semester, $department, $section, $test_type, $testmark, $subject_name, $subject_code);
    if (!$insert_stmt->execute()) {
        echo json_encode(['status' => 'error', 'message' => 'Error inserting record: ' . $insert_stmt->error]);
        exit();
    }
    $test_id = $insert_stmt->insert_id;
    $insert_stmt->close();
}

// Check for course_outcome data
if (!isset($_POST['course_outcome']) || empty($_POST['course_outcome'])) {
    echo json_encode(['status' => 'error', 'message' => 'Error: Missing course_outcome data.']);
    exit();
}

// Delete old co_questions
$delete_query = "DELETE FROM co_questions WHERE test_id = ?";
$delete_stmt = $mysqli->prepare($delete_query);
if (!$delete_stmt) {
    echo json_encode(['status' => 'error', 'message' => 'Error preparing delete statement: ' . $mysqli->error]);
    exit();
}
$delete_stmt->bind_param("i", $test_id);
if (!$delete_stmt->execute()) {
    echo json_encode(['status' => 'error', 'message' => 'Error deleting old co_questions: ' . $delete_stmt->error]);
    exit();
}
$delete_stmt->close();

// Insert new co_questions
foreach ($_POST['course_outcome'] as $question_number => $course_outcome) {
    $blooms_taxonomy = $_POST['blooms_taxonomy'][$question_number];
    $co_marks = $_POST['co_marks'][$question_number];
    $insert_co_query = "INSERT INTO co_questions (test_id, question_number, course_outcome, blooms_taxonomy, co_marks) VALUES (?, ?, ?, ?, ?)";
    $insert_co_stmt = $mysqli->prepare($insert_co_query);
    if (!$insert_co_stmt) {
        echo json_encode(['status' => 'error', 'message' => 'Error preparing insert co_questions statement: ' . $mysqli->error]);
        exit();
    }
    $insert_co_stmt->bind_param("iissi", $test_id, $question_number, $course_outcome, $blooms_taxonomy, $co_marks);
    if (!$insert_co_stmt->execute()) {
        echo json_encode(['status' => 'error', 'message' => 'Error inserting into co_questions: ' . $insert_co_stmt->error]);
        exit();
    }
    $insert_co_stmt->close();
}

// All operations succeeded
$mysqli->close();
echo json_encode(['status' => 'success', 'message' => 'Data saved successfully!']);
?>