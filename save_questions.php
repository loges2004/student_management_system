<?php
session_start();
include 'db.php';

// Check if session variables are set
if (!isset($_SESSION['staff_id'], $_SESSION['staff_name'], $_SESSION['year'], $_SESSION['semester'], $_SESSION['department'], $_SESSION['test_type'], $_SESSION['testmark'], $_SESSION['subject_name'], $_SESSION['subject_code'])) {
    die('Error: Missing session data.');
}

// Fetch session variables
$staff_id = $_SESSION['staff_id'];
$staffname = $_SESSION['staff_name'];
$year = $_SESSION['year'];
$semester = $_SESSION['semester'];
$department = $_SESSION['department'];
$test_type = $_SESSION['test_type'];
$testmark = $_SESSION['testmark'];
$subject_name = $_SESSION['subject_name'];
$subject_code = $_SESSION['subject_code'];


// Check if a record with the same combination already exists
$query = "SELECT id FROM test_results WHERE staff_id = ? AND staffname = ? AND year = ? AND semester = ? AND department = ? AND test_type = ? AND testmark = ? AND subject_name = ? AND subject_code = ?";
$stmt = $mysqli->prepare($query);
if (!$stmt) {
    die('Error preparing statement: ' . $mysqli->error);
}
$stmt->bind_param("isisssiss", $staff_id, $staffname, $year, $semester, $department, $test_type, $testmark, $subject_name, $subject_code);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    // Record exists, update it
    $row = $result->fetch_assoc();
    $test_id = $row['id']; // Get the existing test_id
    $update_query = "UPDATE test_results SET staffname = ?, year = ?, semester = ?, department = ?, test_type = ?, testmark = ?, subject_name = ?, subject_code = ? WHERE id = ?";
    $update_stmt = $mysqli->prepare($update_query);
    if (!$update_stmt) {
        die('Error preparing update statement: ' . $mysqli->error);
    }
    $update_stmt->bind_param("sisssissi", $staffname, $year, $semester, $department, $test_type, $testmark, $subject_name, $subject_code, $test_id);
    
    if (!$update_stmt->execute()) {
        die("Error updating test_results: " . $update_stmt->error);
    }
} else {
    // Record doesn't exist, insert new record
    $insert_query = "INSERT INTO test_results (staff_id, staffname, year, semester, department, test_type, testmark, subject_name, subject_code) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $insert_stmt = $mysqli->prepare($insert_query);
    if (!$insert_stmt) {
        die('Error preparing insert statement: ' . $mysqli->error);
    }
    $insert_stmt->bind_param("isisssiss", $staff_id, $staffname, $year, $semester, $department, $test_type, $testmark, $subject_name, $subject_code);
    
    if ($insert_stmt->execute()) {
        // Get last inserted test_id
        $test_id = $mysqli->insert_id;
    } else {
        die("Error inserting into test_results: " . $insert_stmt->error);
    }
}

// Prepare and execute insert into co_questions table
$insert_co_question_query = "INSERT INTO co_questions (test_id, question_number, course_outcome) VALUES (?, ?, ?)";
$co_stmt = $mysqli->prepare($insert_co_question_query);
if (!$co_stmt) {
    die('Error preparing co_questions statement: ' . $mysqli->error);
}

// Check if 'course_outcome' is set
if (!isset($_POST['course_outcome']) || empty($_POST['course_outcome'])) {
    die("Error: Missing course_outcome data.");
}

foreach ($_POST['course_outcome'] as $question_number => $course_outcome) {
    $co_stmt->bind_param("iis", $test_id, $question_number, $course_outcome);
    if (!$co_stmt->execute()) {
        die("Error inserting into co_questions: " . $co_stmt->error);
    }
}

// Close statements and connection
$co_stmt->close();
if (isset($insert_stmt)) {
    $insert_stmt->close();
}
if (isset($update_stmt)) {
    $update_stmt->close();
}
$mysqli->close();

// Return success message
echo 'Success';
?>