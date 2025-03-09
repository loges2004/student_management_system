<?php
session_start();
include 'db.php';

// Check if session variables are set
if (!isset($_SESSION['staff_id'], $_SESSION['staff_name'], $_SESSION['year'], $_SESSION['semester'], $_SESSION['department'], $_SESSION['test_type'], $_SESSION['testmark'], $_SESSION['subject_name'], $_SESSION['subject_code'])) {
    die('Error: Missing session data.');
}

// Fetch session variables
$staff_id = $_SESSION['staff_id'];
$staffname = strtoupper( $_SESSION['staff_name']);
$year = $_SESSION['year'];
$semester =$_SESSION['semester'];
$department = strtoupper($_SESSION['department']);
$section = strtoupper( $_SESSION['section']);
$test_type = strtoUpper($_SESSION['test_type']);
$testmark = $_SESSION['testmark'];
$subject_name = strtoupper($_SESSION['subject_name']);
$subject_code = strtoupper( $_SESSION['subject_code']);

// Check if a record with the same combination already exists in test_results
$query = "SELECT id FROM test_results WHERE staff_id = ? AND staffname = ? AND year = ? AND semester = ? AND department = ? AND section = ? AND test_type = ? AND testmark = ? AND subject_name = ? AND subject_code = ?";
$stmt = $mysqli->prepare($query);
if (!$stmt) {
    die('Error preparing statement: ' . $mysqli->error);
}
$stmt->bind_param("isissssiss", $staff_id, $staffname, $year, $semester, $department, $section, $test_type, $testmark, $subject_name, $subject_code);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    // Record exists, update it
    $row = $result->fetch_assoc();
    $test_id = $row['id']; // Get the existing test_id
    $update_query = "UPDATE test_results SET staffname = ?, year = ?, semester = ?, department = ?, section = ?, test_type = ?, testmark = ?, subject_name = ?, subject_code = ? WHERE id = ?";
    $update_stmt = $mysqli->prepare($update_query);
    if (!$update_stmt) {
        die('Error preparing update statement: ' . $mysqli->error);
    }
    $update_stmt->bind_param("sissssissi", $staffname, $year, $semester, $department, $section, $test_type, $testmark, $subject_name, $subject_code, $test_id);
    
    if (!$update_stmt->execute()) {
        die("Error updating test_results: " . $update_stmt->error);
    }
    $update_stmt->close();
} else {
    // Record doesn't exist, insert new record
    $insert_query = "INSERT INTO test_results (staff_id, staffname, year, semester, department, section, test_type, testmark, subject_name, subject_code) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $insert_stmt = $mysqli->prepare($insert_query);
    if (!$insert_stmt) {
        die('Error preparing insert statement: ' . $mysqli->error);
    }
    $insert_stmt->bind_param("isissssiss", $staff_id, $staffname, $year, $semester, $department, $section, $test_type, $testmark, $subject_name, $subject_code);
    
    if ($insert_stmt->execute()) {
        // Get last inserted test_id
        $test_id = $mysqli->insert_id;
    } else {
        die("Error inserting into test_results: " . $insert_stmt->error);
    }
    $insert_stmt->close();
}

// Check if 'course_outcome' is set
if (!isset($_POST['course_outcome']) || empty($_POST['course_outcome'])) {
    die("Error: Missing course_outcome data.");
}

$delete_query = "DELETE FROM co_questions WHERE test_id = ?";
$delete_stmt = $mysqli->prepare($delete_query);
if (!$delete_stmt) {
    die('Error preparing delete statement: ' . $mysqli->error);
}
$delete_stmt->bind_param("i", $test_id);
if (!$delete_stmt->execute()) {
    die("Error deleting old co_questions: " . $delete_stmt->error);
}
$delete_stmt->close();

// Now insert fresh data from $_POST['course_outcome']
foreach ($_POST['course_outcome'] as $question_number => $course_outcome) {
    $insert_co_query = "INSERT INTO co_questions (test_id, question_number, course_outcome) VALUES (?, ?, ?)";
    $insert_co_stmt = $mysqli->prepare($insert_co_query);
    if (!$insert_co_stmt) {
        die('Error preparing insert co_questions statement: ' . $mysqli->error);
    }
    $insert_co_stmt->bind_param("iis", $test_id, $question_number, $course_outcome);
    
    if (!$insert_co_stmt->execute()) {
        die("Error inserting into co_questions: " . $insert_co_stmt->error);
    }
    $insert_co_stmt->close();
}

// Close connections and return success
$mysqli->close();
echo 'Success';
?>