<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
header('Content-Type: application/json');

require 'db.php'; // Ensure DB connection is correct

// Validate input
$register_no = $_GET['register_no'] ?? '';
$year = $_GET['year'] ?? '';
$semester = $_GET['semester'] ?? '';
$department = $_GET['department'] ?? '';
$section = $_GET['section'] ?? '';
$test_type = $_GET['test_type'] ?? '';
$subject_code = $_GET['subject_code'] ?? '';

if (!$register_no) {
    echo json_encode(['success' => false, 'error' => 'Register number missing']);
    exit;
}

// Fetch marks
$query = "SELECT question_number, marks, attended, total_marks 
          FROM student_marks 
          WHERE register_no = ? 
          AND year = ? 
          AND semester = ? 
          AND department = ? 
          AND section = ? 
          AND test_type = ? 
          AND subject_code = ?";
$stmt = $mysqli->prepare($query);
if (!$stmt) {
    echo json_encode(['success' => false, 'error' => 'Database error']);
    exit;
}

$stmt->bind_param("sisssss", $register_no, $year, $semester, $department, $section, $test_type, $subject_code);
if (!$stmt->execute()) {
    echo json_encode(['success' => false, 'error' => 'Database error']);
    exit;
}

$result = $stmt->get_result();
$marksData = [];
$total_marks = 0;

while ($row = $result->fetch_assoc()) {
    $marksData[$row['question_number']] = [
        'marks' => $row['marks'],
        'attended' => $row['attended']
    ];
    $total_marks += $row['marks'];
}

if (!empty($marksData)) {
    echo json_encode(['success' => true, 'marks' => $marksData, 'total_marks' => $total_marks]);
} else {
    echo json_encode(['success' => false, 'error' => 'No marks found']);
}

$stmt->close();
exit;
?>