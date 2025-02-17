<?php
session_start();
include('db.php');

// Retrieve original marks and counts from POST data
$countsArray = json_decode($_POST['counts'], true);
$originalMarksArray = json_decode($_POST['original_marks'], true);
$questionCount = (int)$_POST['questionCount'];

// Store in session for the next page load
$_SESSION['questionCount'] = $questionCount;
$_SESSION['marksArray'] = $originalMarksArray;
$_SESSION['countsArray'] = $countsArray;

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    $_SESSION['failed'] = "Invalid request method!";
    header("Location: test_enter.php");
    exit;
}

// Retrieve and validate form data (convert register_no to uppercase)
$register_no = isset($_POST['register_no']) ? strtoupper(trim($_POST['register_no'])) : '';
$marks = $_POST['marks'] ?? [];
$attended = $_POST['attended'] ?? [];
$attendance = isset($_POST['attendance']) ? 'Present' : 'Absent';
$questionCount = (int)($_POST['questionCount'] ?? 0);
$testmark = (int)($_POST['testmark'] ?? 0);

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
if ($testmark <= 0) $errors[] = "Test mark must be greater than 0.";

if (!empty($errors)) {
    $_SESSION['failed'] = implode("<br>", $errors);
    header("Location: test_enter.php?questionCount=$questionCount");
    exit;
}

try {
    $mysqli->begin_transaction();

    // Get student details and convert to uppercase
    $stmt = $mysqli->prepare("SELECT student_id, student_name, section FROM stud WHERE register_no = ?");
    $stmt->bind_param("s", $register_no);
    $stmt->execute();
    $student = $stmt->get_result()->fetch_assoc();
    $stmt->close();

    if (!$student) throw new Exception("Student not found!");
    $student_id = $student['student_id'];
    $student_name = strtoupper($student['student_name']);
    $section = strtoupper($student['section']);

    // Fetch or create test configuration (convert to uppercase)
    $stmt = $mysqli->prepare("SELECT id, test_type, subject_code, subject_name FROM test_results WHERE year = ? AND semester = ? AND department = ? AND section = ? AND test_type = ? AND subject_code = ?");
    $stmt->bind_param("iissss", $_POST['year'], $_POST['semester'], $_POST['department'], $_POST['section'], $_POST['test_type'], $_POST['subject_code']);
    $stmt->execute();
    $test_result = $stmt->get_result()->fetch_assoc();
    $stmt->close();

    if (!$test_result) {
        // Insert new test with uppercase values
        $insert_test = $mysqli->prepare("INSERT INTO test_results (year, semester, department, section, test_type, subject_code, subject_name, testmark) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $department_upper = strtoupper($_POST['department']);
        $section_upper = strtoupper($_POST['section']);
        $test_type_upper = strtoupper($_POST['test_type']);
        $subject_code_upper = strtoupper($_POST['subject_code']);
        $subject_name_upper = strtoupper($_POST['subject_name']);
        $insert_test->bind_param(
            "iisssssi",
            $_POST['year'],
            $_POST['semester'],
            $department_upper,
            $section_upper,
            $test_type_upper,
            $subject_code_upper,
            $subject_name_upper,
            $testmark
        );
        if (!$insert_test->execute()) {
            throw new Exception("Error creating test configuration: " . $insert_test->error);
        }
        $test_id = $mysqli->insert_id;
        $test_type_db = $test_type_upper;
        $subject_code_db = $subject_code_upper;
        $subject_name_db = $subject_name_upper;
    } else {
        $test_id = $test_result['id'];
        $test_type_db = strtoupper($test_result['test_type']);
        $subject_code_db = strtoupper($test_result['subject_code']);
        $subject_name_db = strtoupper($test_result['subject_name']);

        // Update testmark if needed
        if ($test_result['testmark'] != $testmark) {
            $update_testmark = $mysqli->prepare("UPDATE test_results SET testmark = ? WHERE id = ?");
            $update_testmark->bind_param("ii", $testmark, $test_id);
            if (!$update_testmark->execute()) {
                throw new Exception("Error updating testmark: " . $update_testmark->error);
            }
            $update_testmark->close();
        }
    }

    // Prepare COs for questions
    $co_stmt = $mysqli->prepare("SELECT course_outcome FROM co_questions WHERE test_id = ? AND question_number = ?");
    $insert_mark = $mysqli->prepare("
        INSERT INTO student_marks (
            test_id, register_no, student_name, section, question_number, marks, attended, 
            course_outcome, test_type, testmark, subject_code, subject_name, attendance, total_marks,
            year, department, semester, student_id
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
        ON DUPLICATE KEY UPDATE
            marks = VALUES(marks),
            attended = VALUES(attended),
            attendance = VALUES(attendance),
            section = VALUES(section),
            total_marks = VALUES(total_marks),
            year = VALUES(year),
            department = VALUES(department),
            semester = VALUES(semester),
            student_id = VALUES(student_id)
    ");

    // Calculate total marks and insert/update
    $total_marks = 0;
    foreach ($marks as $questionNo => $mark) {
        $mark = (int)$mark;
        $is_attended = isset($attended[$questionNo]) ? (int)$attended[$questionNo] : 0;
        $total_marks += $mark;

        $co_stmt->bind_param("ii", $test_id, $questionNo);
        $co_stmt->execute();
        $co_result = $co_stmt->get_result()->fetch_assoc();
        $course_outcome = $co_result['course_outcome'] ?? null;

        // Convert attendance to uppercase
        $attendance_upper = strtoupper($attendance);

        $insert_mark->bind_param(
            "isssiiissssssiissi", // Adjust the types based on your schema
            $test_id,
            $register_no,
            $student_name,
            $section,
            $questionNo,
            $mark,
            $is_attended,
            $course_outcome,
            $test_type_db,
            $testmark,
            $subject_code_db,
            $subject_name_db,
            $attendance_upper,
            $total_marks,
            $_POST['year'], // Add year
            $_POST['department'], // Add department
            $_POST['semester'], // Add semester
            $student_id // Add student_id
        );
        if (!$insert_mark->execute()) {
            throw new Exception("Error saving marks for question $questionNo: " . $insert_mark->error);
        }
    }

    // Validate total marks
    if ($total_marks > $testmark) {
        throw new Exception("Total marks ($total_marks) exceed the maximum allowed marks ($testmark) for the test.");
    }

    // Update total_marks
    $update_total_marks = $mysqli->prepare("UPDATE student_marks SET total_marks = ? WHERE test_id = ? AND register_no = ?");
    $update_total_marks->bind_param("iis", $total_marks, $test_id, $register_no);
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