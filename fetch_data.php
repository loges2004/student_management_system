<?php
include('db.php');

// Fetch staff distribution by department
$staffQuery = "SELECT department, COUNT(*) as count FROM staff GROUP BY department";
$staffResult = $mysqli->query($staffQuery);
if (!$staffResult) {
    die(json_encode(['error' => 'Staff Query Error: ' . $mysqli->error]));
}
$staffData = [
    'labels' => [],
    'values' => []
];
while ($row = $staffResult->fetch_assoc()) {
    $staffData['labels'][] = $row['department'];
    $staffData['values'][] = (int)$row['count'];
}

// Fetch student distribution by year
$studentQuery = "SELECT years, COUNT(*) as count FROM stud GROUP BY years";
$studentResult = $mysqli->query($studentQuery);
if (!$studentResult) {
    die(json_encode(['error' => 'Student Query Error: ' . $mysqli->error]));
}
$studentData = [
    'labels' => [],
    'values' => []
];
while ($row = $studentResult->fetch_assoc()) {
    $studentData['labels'][] = "Year " . $row['years'];
    $studentData['values'][] = (int)$row['count'];
}

// Fetch recent staff
$recentStaffQuery = "SELECT staff_id, first_name, last_name, department FROM staff ORDER BY created_at DESC LIMIT 5";
$recentStaffResult = $mysqli->query($recentStaffQuery);
if (!$recentStaffResult) {
    die(json_encode(['error' => 'Recent Staff Query Error: ' . $mysqli->error]));
}
$recentStaff = [];
while ($row = $recentStaffResult->fetch_assoc()) {
    $recentStaff[] = $row;
}

// Fetch recent students (UPDATED QUERY)
$recentStudentQuery = "SELECT register_no, student_name, department FROM stud ORDER BY student_id DESC LIMIT 5";
$recentStudentResult = $mysqli->query($recentStudentQuery);
if (!$recentStudentResult) {
    die(json_encode(['error' => 'Recent Student Query Error: ' . $mysqli->error]));
}
$recentStudents = [];
while ($row = $recentStudentResult->fetch_assoc()) {
    $recentStudents[] = $row;
}

// Return JSON response
header('Content-Type: application/json');
echo json_encode([
    'staff' => $staffData,
    'student' => $studentData,
    'recentStaff' => $recentStaff,
    'recentStudents' => $recentStudents
]);
?>