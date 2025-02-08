<?php
session_start();
include('db.php');

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    $_SESSION['failed'] = "Invalid request method!";
    header("Location: test_enter.php");
    exit;
}

// Retrieve and validate form data
$register_no = $_POST['register_no'] ?? '';
$marks = $_POST['marks'] ?? [];
$attended = $_POST['attended'] ?? [];
$attendance = isset($_POST['attendance']) ? 'P' : 'A';
$questionCount = (int)($_POST['questionCount'] ?? 0);

// Validate required fields
$errors = [];
if (empty($register_no)) $errors[] = "Register Number is required.";
if ($questionCount === 0) $errors[] = "Number of questions is missing.";
if (empty($_POST['year'])) $errors[] = "Year is required.";
if (empty($_POST['semester'])) $errors[] = "Semester is required.";
if (empty($_POST['department'])) $errors[] = "Department is required.";
if (empty($_POST['section'])) $errors[] = "Section is required.";
if (empty($_POST['test_type'])) $errors[] = "Test Type is required.";
if (empty($_POST['subject_code'])) $errors[] = "Subject Code is required.";
if (empty($_POST['testmark'])) $errors[] = "test mark is required.";

if (!empty($errors)) {
    $_SESSION['failed'] = implode("<br>", $errors);
    header("Location: test_enter.php?questionCount=$questionCount");
    exit;
}

try {
    $mysqli->begin_transaction();

    // Get student details including section
    $stmt = $mysqli->prepare("SELECT student_id, student_name, section FROM stud WHERE register_no = ?");
    $stmt->bind_param("s", $register_no);
    $stmt->execute();
    $student = $stmt->get_result()->fetch_assoc();
    $stmt->close();

    if (!$student) throw new Exception("Student not found!");
    $student_id = $student['student_id'];
    $student_name = $student['student_name'];
    $section = $student['section']; // Retrieve section from stud table

    // Fetch or create test configuration
    $stmt = $mysqli->prepare("SELECT id, test_type, testmark, subject_code, subject_name FROM test_results WHERE year = ? AND semester = ? AND department = ? AND section = ? AND test_type = ? AND subject_code = ?");
    $stmt->bind_param("iissss", $_POST['year'], $_POST['semester'], $_POST['department'], $_POST['section'], $_POST['test_type'], $_POST['subject_code']);
    $stmt->execute();
    $test_result = $stmt->get_result()->fetch_assoc();
    $stmt->close();

    if (!$test_result) {
        // Insert a new record into test_results if no matching record is found
        $insert_test = $mysqli->prepare("INSERT INTO test_results (year, semester, department, section, test_type, subject_code, subject_name, testmark) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $testmark ;
        $insert_test->bind_param("iisssssi", $_POST['year'], $_POST['semester'], $_POST['department'], $_POST['section'], $_POST['test_type'], $_POST['subject_code'], $_POST['subject_name'], $testmark);
        if (!$insert_test->execute()) {
            throw new Exception("Error creating test configuration: " . $insert_test->error);
        }
        $test_id = $mysqli->insert_id; // Get the ID of the newly inserted record
    } else {
        $test_id = $test_result['id'];
        $test_type_db = $test_result['test_type'];
        $testmark_db = $test_result['testmark'];
        $subject_code_db = $test_result['subject_code'];
        $subject_name_db = $test_result['subject_name'];
    }

    // Prepare COs for questions
    $co_stmt = $mysqli->prepare("SELECT course_outcome FROM co_questions WHERE test_id = ? AND question_number = ?");
    $insert_mark = $mysqli->prepare("INSERT INTO student_marks (test_id, student_id, register_no, question_number, marks, attended, course_outcome, student_name, test_type, testmark, subject_code, subject_name, attendance, section) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?) ON DUPLICATE KEY UPDATE marks = VALUES(marks), attended = VALUES(attended), attendance = VALUES(attendance), section = VALUES(section)");

    // Calculate total marks
    $total_marks = 0;
    foreach ($marks as $questionNo => $mark) {
        $mark = (int)$mark;
        $is_attended = isset($attended[$questionNo]) ? (int)$attended[$questionNo] : 0;
        $total_marks += $mark;

        $co_stmt->bind_param("ii", $test_id, $questionNo);
        $co_stmt->execute();
        $co_result = $co_stmt->get_result()->fetch_assoc();
        $course_outcome = $co_result['course_outcome'] ?? null;

        $insert_mark->bind_param("iisiiissssssss", $test_id, $student_id, $register_no, $questionNo, $mark, $is_attended, $course_outcome, $student_name, $test_type_db, $testmark_db, $subject_code_db, $subject_name_db, $attendance, $section);
        if (!$insert_mark->execute()) {
            throw new Exception("Error saving marks for question $questionNo: " . $insert_mark->error);
        }
    }
    if ($total_marks > $testmark_db) {
        throw new Exception("Total marks ($total_marks) exceed the maximum allowed marks ($testmark_db) for the test.");
    }

    // Update total_marks for all rows of this student and test
    $update_total_marks = $mysqli->prepare("UPDATE student_marks SET total_marks = ? WHERE test_id = ? AND student_id = ?");
    $update_total_marks->bind_param("iii", $total_marks, $test_id, $student_id);
    $update_total_marks->execute();
    $update_total_marks->close();

    $mysqli->commit();
    $_SESSION['success'] = "Marks for $student_name saved successfully! Total Marks: $total_marks";
} catch (Exception $e) {
    $mysqli->rollback();
    $_SESSION['failed'] = "Error: " . $e->getMessage();
} finally {
    if (isset($co_stmt)) $co_stmt->close();
    if (isset($insert_mark)) $insert_mark->close();
    $mysqli->close();
    header("Location: test_enter.php?questionCount=$questionCount");
    exit;
}