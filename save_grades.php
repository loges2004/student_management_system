<?php
session_start();
require 'db.php';

$register_no = $_POST['register_no'];
$semester = $_POST['semester'];
$grades = $_POST['grades'];
$section = $_POST['section'];

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

$student_name = $student['student_name'];
$year = $student['years'];
$department = $student['department'];

// Insert or update grades for each subject
foreach ($grades as $subject_id => $grade) {
    // Fetch subject details (subject_code, subject_name, credit_points)
    $stmt = $mysqli->prepare("SELECT subject_code, subject_name, credit_points FROM subjects WHERE subject_id = ?");
    if ($stmt === false) {
        die('MySQL prepare error: ' . $mysqli->error);
    }

    $stmt->bind_param('i', $subject_id);
    $stmt->execute();
    $subject = $stmt->get_result()->fetch_assoc();

    if (!$subject) {
        die("Subject not found.");
    }

    $subject_code = $subject['subject_code'];
    $subject_name = $subject['subject_name'];
    $credit_points = $subject['credit_points'];

    // Insert or update grades
    $stmt = $mysqli->prepare("
        INSERT INTO student_grades (register_no, student_name, subject_id, subject_code, subject_name, grade, semester, department, section, years) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
        ON DUPLICATE KEY UPDATE 
            student_name = VALUES(student_name),
            subject_code = VALUES(subject_code),
            subject_name = VALUES(subject_name),
            grade = VALUES(grade), 
            semester = VALUES(semester),
            section = VALUES(section),
            years = VALUES(years)
    ");

    if ($stmt === false) {
        die('MySQL prepare error: ' . $mysqli->error);
    }

    $stmt->bind_param('ssissssssi', $register_no, $student_name, $subject_id, $subject_code, $subject_name, $grade, $semester, $department, $section, $year);
    $stmt->execute();
}

// Calculate CGPA based on grades and credit points
$cgpa = 0;
$credit_total = 0;
$grade_points_total = 0;

foreach ($grades as $subject_id => $grade) {
    // Fetch subject details (credit_points)
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

$truncated_cgpa = floor($cgpa * 100) / 100;

// Update CGPA in all rows for the student in the current semester
$stmt = $mysqli->prepare("
    UPDATE student_grades 
    SET cgpa_mark = ? 
    WHERE register_no = ? 
    AND semester = ? 
    AND TRIM(department) = TRIM(?) 
    AND section = ?
    AND years = ?
");

if ($stmt === false) {
    die('MySQL prepare error: ' . $mysqli->error);
}

$stmt->bind_param('dsssss', $truncated_cgpa, $register_no, $semester, $department, $section, $year);
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