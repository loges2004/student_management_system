<?php
session_start();
require 'db.php';

$register_no = $_POST['register_no'];
$semester = $_POST['semester'];
$grades = $_POST['grades'];

// Fetch student by register number
$stmt = $mysqli->prepare("SELECT student_id, student_name, years, department FROM stud WHERE register_no = ?");
if ($stmt === false) {
    die('MySQL prepare error: ' . $mysqli->error);
}

$stmt->bind_param('s', $register_no);
$stmt->execute();
$student = $stmt->get_result()->fetch_assoc();

if (!$student) {
    die("Student not found.");
}

$student_id = $student['student_id'];
$student_name = $student['student_name'];
$year = $student['years'];
$department = $student['department'];
foreach ($grades as $subject_id => $grade) {
    // Prepare the SQL query
    $stmt = $mysqli->prepare("
        INSERT INTO student_grades (student_id, subject_id, grade, semester, department) 
        VALUES (?, ?, ?, ?, ?)
        ON DUPLICATE KEY UPDATE 
            grade = VALUES(grade), 
            semester = VALUES(semester)
    ");

    if ($stmt === false) {
        die('MySQL prepare error: ' . $mysqli->error);
    }

    // Bind parameters
    $stmt->bind_param('iisss', $student_id, $subject_id, $grade, $semester, $department);
    $stmt->execute();
}

// Calculate CGPA based on grades and credit points
$cgpa = 0;
$credit_total = 0;
$grade_points_total = 0;

foreach ($grades as $subject_id => $grade) {
    // Get credit points for the subject
    $stmt = $mysqli->prepare("SELECT credit_points FROM subjects WHERE subject_id = ?");
    if ($stmt === false) {
        die('MySQL prepare error: ' . $mysqli->error);
    }

    $stmt->bind_param('i', $subject_id);
    $stmt->execute();
    $subject = $stmt->get_result()->fetch_assoc();

    if (!$subject) {
        die("Subject not found.");
    }

    $credit_points = $subject['credit_points'];

    // Convert grade to grade points
    switch ($grade) {
        case 'O': $grade_points = 10; break;
        case 'A+': $grade_points = 9; break;
        case 'A': $grade_points = 8; break;
        case 'B+': $grade_points = 7; break;
        case 'B': $grade_points = 6; break;
        case 'C': $grade_points = 5; break;
        default: $grade_points = 0; break;
    }

    $grade_points_total += $grade_points * $credit_points;
    $credit_total += $credit_points;
}

if ($credit_total > 0) {
    $cgpa = $grade_points_total / $credit_total;
}

// Truncate CGPA to 2 decimal places
$truncated_cgpa = floor($cgpa * 100) / 100;

// Insert or update CGPA in the cgpa_table
$stmt = $mysqli->prepare("INSERT INTO cgpa_table (register_no, student_name, years, department, semester, cgpa_mark) 
                          VALUES (?, ?, ?, ?, ?, ?) 
                          ON DUPLICATE KEY UPDATE 
                          student_name = VALUES(student_name), 
                          years = VALUES(years), 
                          department = VALUES(department), 
                          semester = VALUES(semester), 
                          cgpa_mark = VALUES(cgpa_mark)");

if ($stmt === false) {
    die('MySQL prepare error: ' . $mysqli->error);
}

$stmt->bind_param('ssissd', $register_no, $student_name, $year, $department, $semester, $truncated_cgpa);
if (!$stmt->execute()) {
    die('MySQL execute error: ' . $stmt->error);
}

$stmt->close();
$mysqli->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>CGPA Result</title>
    <style>
        .container {
            margin-top: 50px;
            max-width: 600px;
            padding: 20px;
            background-color: #ffffff;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        .cgpa-box {
            font-size: 1.5rem;
            text-align: center;
            margin-bottom: 20px;
        }
    </style>
    <script>
        function goBack() {
            window.history.back();
        }
    </script>
</head>
<body>
    <div class="container">
        <h2 class="text-center">CGPA Calculation Result</h2>
        <div class="cgpa-box">
            <p><strong>Register No:</strong> <?= htmlspecialchars($register_no) ?></p>
            <p><strong>Student Name:</strong> <?= htmlspecialchars($student_name) ?></p>
            <p><strong>CGPA:</strong> <?= number_format($truncated_cgpa, 2, '.', '') ?></p>
        </div>
        <button class="btn btn-primary" onclick="goBack()">Back</button>
    </div>
</body>
</html>
