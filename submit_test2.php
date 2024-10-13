<?php
session_start();
// Include your database connection
include('db.php');

// Retrieve values from the POST request and sanitize them
$year = $_POST['year'];
$semester = $_POST['semester'];
$department = $_POST['department'];
$test_type = $_POST['test_type'];
$subject_name = $_POST['subject_name'];
$subject_code = $_POST['subject_code'];
$register_no = $_POST['register_no'];
$student_name = $_POST['student_name'];
$total_marks = $_POST['total_marks'];

// Initialize arrays to hold marks and course outcomes
$marks = [];
$co_marks = [];

// Fetch the student ID based on the registration number
$sql = "SELECT student_id, student_name FROM stud WHERE register_no = ?";
$stmt = $mysqli->prepare($sql);
$stmt->bind_param("s", $register_no);
$stmt->execute();
$result = $stmt->get_result();

// Check if the student exists
if ($result->num_rows === 0) {
    die("No student found with the provided registration number.");
}
$row = $result->fetch_assoc();
$student_id = $row['student_id'];
$student_name = $row['student_name'];

// Fetch subject ID based on subject name and code
$sql = "SELECT subject_id FROM subjects WHERE subject_code = ? AND subject_name = ?";
$stmt = $mysqli->prepare($sql);
$stmt->bind_param("ss", $subject_code, $subject_name);
$stmt->execute();
$result = $stmt->get_result();

// Check if the subject exists
if ($result->num_rows === 0) {
    die("No subject found with the provided code and name.");
}
$row = $result->fetch_assoc();
$subject_id = $row['subject_id'];

// Fetch course outcomes for the given semester, department, subject name, subject code, and test type
$sql = "SELECT * FROM course_outcomes100 WHERE semester = ? AND department = ? AND subject_name = ? AND subject_code = ? AND test_type = ?";
$stmt = $mysqli->prepare($sql);
$stmt->bind_param("sssss", $semester, $department, $subject_name, $subject_code, $test_type);
$stmt->execute();
$result = $stmt->get_result();

// Check if we got any results
if ($result->num_rows === 0) {
    die("No course outcomes found for the provided criteria.");
}

// Fetch course outcome data
$row = $result->fetch_assoc();

// Part A: Assuming you have 10 questions
for ($i = 1; $i <= 10; $i++) {
    $marks[$i] = isset($_POST["part_a_$i"]) ? $_POST["part_a_$i"] : null; // Set to null if not set
    $co_marks[$i] = isset($row["co_question_$i"]) ? $row["co_question_$i"] : null; // Correct access for CO
}

// Part B: questions 11A, 11B, 12A, 12B, 13A, 13B, 14A, 14B, 15A, 15B, 16A, 16B
$partBQuestions = [
    '11a', '11b',
    '12a', '12b',
    '13a', '13b',
    '14a', '14b',
    '15a', '15b',
    '16a', '16b'
];

foreach ($partBQuestions as $question) {
    // Accessing POST values with lowercase
    $marks[$question] = isset($_POST["part_b_$question"]) ? $_POST["part_b_$question"] : null;
    
    // Accessing CO marks with the same lowercase format
    $co_marks[$question] = isset($row["co_question_$question"]) ? $row["co_question_$question"] : null;
}

// Determine the table name based on the test type
$table_name = $test_type;

// Check if the record exists before deciding to insert or update
$checkSql = "SELECT COUNT(*) as count FROM $table_name WHERE student_id = ? AND subject_id = ?";
$checkStmt = $mysqli->prepare($checkSql);
$checkStmt->bind_param("si", $student_id, $subject_id);
$checkStmt->execute();
$checkResult = $checkStmt->get_result();
$checkRow = $checkResult->fetch_assoc();

// Prepare update or insert statement
if ($checkRow['count'] > 0) {
    // Update existing record
    $updateSql = "UPDATE $table_name SET 
        registration_no = ?, student_name = ?, years = ?, department = ?, 
        subject_id = ?, subject_code = ?, subject_name = ?, 
        q1_marks = ?, q1_co = ?, q2_marks = ?, q2_co = ?, 
        q3_marks = ?, q3_co = ?, q4_marks = ?, q4_co = ?, 
        q5_marks = ?, q5_co = ?, q6_marks = ?, q6_co = ?, 
        q7_marks = ?, q7_co = ?, q8_marks = ?, q8_co = ?, 
        q9_marks = ?, q9_co = ?, q10_marks = ?, q10_co = ?, 
        q11A_marks = ?, q11A_co = ?, q11B_marks = ?, q11B_co = ?, 
        q12A_marks = ?, q12A_co = ?, q12B_marks = ?, q12B_co = ?, 
        q13A_marks = ?, q13A_co = ?, q13B_marks = ?, q13B_co = ?, 
        q14A_marks = ?, q14A_co = ?, q14B_marks = ?, q14B_co = ?, 
        q15A_marks = ?, q15A_co = ?, q15B_marks = ?, q15B_co = ?, 
        q16A_marks = ?, q16A_co = ?, q16B_marks = ?, q16B_co = ?, 
        total_marks = ? 
        WHERE student_id = ? AND subject_id = ?";
    
    $stmt = $mysqli->prepare($updateSql);
    $stmt->bind_param("ssssssssssssssssssssssssssssssssssssssssssssssssssssss", 
        $register_no, $student_name, $year, $department, 
        $subject_id, $subject_code, $subject_name, 
        $marks[1], $co_marks[1], $marks[2], $co_marks[2], 
        $marks[3], $co_marks[3], $marks[4], $co_marks[4], 
        $marks[5], $co_marks[5], $marks[6], $co_marks[6], 
        $marks[7], $co_marks[7], $marks[8], $co_marks[8], 
        $marks[9], $co_marks[9], $marks[10], $co_marks[10], 
        $marks['11a'], $co_marks['11a'], $marks['11b'], $co_marks['11b'], 
        $marks['12a'], $co_marks['12a'], $marks['12b'], $co_marks['12b'], 
        $marks['13a'], $co_marks['13a'], $marks['13b'], $co_marks['13b'], 
        $marks['14a'], $co_marks['14a'], $marks['14b'], $co_marks['14b'], 
        $marks['15a'], $co_marks['15a'], $marks['15b'], $co_marks['15b'], 
        $marks['16a'], $co_marks['16a'], $marks['16b'], $co_marks['16b'], 
        $total_marks, $student_id, $subject_id
    );

    if ($stmt->execute()) {
        // Redirect to the previous page
        $_SESSION['message'] = "Data updated successfully for student: " . htmlspecialchars($student_name);
        header("Location: " . $_SERVER['HTTP_REFERER']);
        exit();
    } else {
        die("Update failed: " . $stmt->error);
    }
    
} else {
    // Insert new record
    $insertSql = "INSERT INTO $table_name 
        (student_id, registration_no, student_name, years, department, subject_id, subject_code, subject_name, 
        q1_marks, q1_co, q2_marks, q2_co, q3_marks, q3_co, q4_marks, q4_co, 
        q5_marks, q5_co, q6_marks, q6_co, q7_marks, q7_co, q8_marks, q8_co, 
        q9_marks, q9_co, q10_marks, q10_co, 
        q11A_marks, q11A_co, q11B_marks, q11B_co, 
        q12A_marks, q12A_co, q12B_marks, q12B_co, 
        q13A_marks, q13A_co, q13B_marks, q13B_co, 
        q14A_marks, q14A_co, q14B_marks, q14B_co, 
        q15A_marks, q15A_co, q15B_marks, q15B_co, 
        q16A_marks, q16A_co, q16B_marks, q16B_co, 
        total_marks) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

    $stmt = $mysqli->prepare($insertSql);
    $stmt->bind_param("sssssssssssssssssssssssssssssssssssssssssssssssssss", 
        $student_id, $register_no, $student_name, $year, $department, 
        $subject_id, $subject_code, $subject_name, 
        $marks[1], $co_marks[1], $marks[2], $co_marks[2], 
        $marks[3], $co_marks[3], $marks[4], $co_marks[4], 
        $marks[5], $co_marks[5], $marks[6], $co_marks[6], 
        $marks[7], $co_marks[7], $marks[8], $co_marks[8], 
        $marks[9], $co_marks[9], $marks[10], $co_marks[10], 
        $marks['11a'], $co_marks['11a'], $marks['11b'], $co_marks['11b'], 
        $marks['12a'], $co_marks['12a'], $marks['12b'], $co_marks['12b'], 
        $marks['13a'], $co_marks['13a'], $marks['13b'], $co_marks['13b'], 
        $marks['14a'], $co_marks['14a'], $marks['14b'], $co_marks['14b'], 
        $marks['15a'], $co_marks['15a'], $marks['15b'], $co_marks['15b'], 
        $marks['16a'], $co_marks['16a'], $marks['16b'], $co_marks['16b'], 
        $total_marks
    );

    if ($stmt->execute()) {
        $_SESSION['message'] = "Data updated successfully for student: " . htmlspecialchars($student_name);
        header("Location: " . $_SERVER['HTTP_REFERER']);
    } else {
        die("Insertion failed: " . $stmt->error);
    }
}

// Close connections
$stmt->close();
$mysqli->close();
?>
