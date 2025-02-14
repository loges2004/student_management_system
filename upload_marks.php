<?php
include('db.php');
session_start();

// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Validate session variables
$required_session = ['year', 'semester', 'department', 'section', 'test_type', 'subject_name', 'subject_code', 'testmark'];
foreach ($required_session as $var) {
    if (!isset($_SESSION[$var])) {
        die("Session expired or invalid. Please reconfigure the test.");
    }
}

require 'vendor/autoload.php';
use PhpOffice\PhpSpreadsheet\IOFactory;

// Function to get CO based on question number and countsArray
function getCourseOutcome($questionNumber, $countsArray) {
    $coIndex = 0;
    $currentCount = 0;

    foreach ($countsArray as $count) {
        $currentCount += $count;
        if ($questionNumber <= $currentCount) {
            return 'CO' . ($coIndex + 1);
        }
        $coIndex++;
    }
    return 'CO1';
}

try {
    if (isset($_FILES['excelFile'])) {
        $questionCount = (int)$_POST['questionCount'];
        $marksArray = json_decode($_POST['marks'], true); // Max marks for each question
        $countsArray = json_decode($_POST['counts'], true); // Number of questions per CO

        // Debug: Log marksArray and countsArray
        error_log("marksArray: " . print_r($marksArray, true));
        error_log("countsArray: " . print_r($countsArray, true));

        // Get or create test_id from test_results
        $stmt = $mysqli->prepare("
            SELECT id FROM test_results 
            WHERE year = ? 
            AND semester = ? 
            AND department = ? 
            AND section = ? 
            AND test_type = ? 
            AND subject_code = ?
        ");
        $stmt->bind_param(
            "iissss",
            $_SESSION['year'],
            $_SESSION['semester'],
            $_SESSION['department'],
            $_SESSION['section'],
            $_SESSION['test_type'],
            $_SESSION['subject_code']
        );
        $stmt->execute();
        $result = $stmt->get_result();
        $test = $result->fetch_assoc();

        if ($test) {
            $test_id = $test['id'];
        } else {
            // Insert new test entry
            $insertStmt = $mysqli->prepare("
                INSERT INTO test_results (
                    staffname, year, semester, department, 
                    section, test_type, testmark, subject_name, subject_code, staff_id
                ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
            ");
            $staffname = $_SESSION['staffname'] ?? '';
            $staff_id = $_SESSION['staff_id'] ?? 0;
            $insertStmt->bind_param(
                "siisssissi",
                $staffname,
                $_SESSION['year'],
                $_SESSION['semester'],
                $_SESSION['department'],
                $_SESSION['section'],
                $_SESSION['test_type'],
                $_SESSION['testmark'],
                $_SESSION['subject_name'],
                $_SESSION['subject_code'],
                $staff_id
            );
            $insertStmt->execute();
            $test_id = $mysqli->insert_id;
        }

        // Fetch CO mapping from co_questions table
        $co_mapping = [];
        $co_stmt = $mysqli->prepare("SELECT question_number, course_outcome FROM co_questions WHERE test_id = ?");
        $co_stmt->bind_param("i", $test_id);
        $co_stmt->execute();
        $co_result = $co_stmt->get_result();
        while ($row = $co_result->fetch_assoc()) {
            $co_mapping[$row['question_number']] = $row['course_outcome'];
        }

        $file = $_FILES['excelFile']['tmp_name'];
        $spreadsheet = IOFactory::load($file);
        $sheet = $spreadsheet->getActiveSheet();
        $rows = $sheet->toArray();

        array_shift($rows); // Remove header

        $mysqli->autocommit(false);
        $mysqli->begin_transaction();

        foreach ($rows as $row) {
            $register_no = $row[0];
            $student_name = $row[1];
            $total_mark = array_pop($row);
            $marks = array_slice($row, 2, $questionCount);

            // Debug: Log marks for each student
            error_log("Processing student: $register_no");
            error_log("Marks: " . print_r($marks, true));

            // Validate marks
            foreach ($marks as $mark) {
                if (!is_numeric($mark)) {
                    throw new Exception("Invalid mark value for student $register_no: $mark");
                }
            }

            // Calculate total marks
            $calculated_total = array_sum($marks);
            if ($calculated_total != $total_mark) {
                throw new Exception("Total marks mismatch for $register_no. Expected: $total_mark, Calculated: $calculated_total");
            }

            // Get student ID
            $stmt = $mysqli->prepare("SELECT student_id FROM stud WHERE register_no = ?");
            $stmt->bind_param("s", $register_no);
            $stmt->execute();
            $student = $stmt->get_result()->fetch_assoc();
            $student_id = $student['student_id'] ?? null;

            if (!$student_id) {
                throw new Exception("Student not found: $register_no");
            }

            // Delete existing marks
            $delete_stmt = $mysqli->prepare("DELETE FROM student_marks WHERE student_id = ? AND test_id = ?");
            $delete_stmt->bind_param("ii", $student_id, $test_id);
            $delete_stmt->execute();

            // Insert new marks
            for ($i = 0; $i < $questionCount; $i++) {
                $question_number = $i + 1;
                $maxMark = $marksArray[$i] ?? 0; // Get max mark from marksArray
                $mark = $marks[$i]; // Use the actual mark from the Excel file
                $attended = $mark > 0 ? 1 : 0;

                // Debug: Log maxMark and actual mark
                error_log("Question $question_number: Max Mark = $maxMark, Actual Mark = $mark");

                // Get CO from mapping or calculate
                $co = $co_mapping[$question_number] ?? getCourseOutcome($question_number, $countsArray);

                // Prepare insert statement
                $insert_stmt = $mysqli->prepare("
                    INSERT INTO student_marks (
                        test_id, student_id, register_no, student_name, section,
                        question_number, marks, attended, course_outcome,
                        test_type, testmark, subject_code, subject_name, attendance, total_marks
                    ) VALUES (
                        ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?
                    )
                ");

                $attendance = ($total_mark > 0) ? 'Present' : 'Absent';

                $insert_stmt->bind_param(
                    "iiissiiissssssi",
                    $test_id,
                    $student_id,
                    $register_no,
                    $student_name,
                    $_SESSION['section'],
                    $question_number,
                    $mark,
                    $attended,
                    $co,
                    $_SESSION['test_type'],
                    $_SESSION['testmark'],
                    $_SESSION['subject_code'],
                    $_SESSION['subject_name'],
                    $attendance,
                    $total_mark
                );

                if (!$insert_stmt->execute()) {
                    throw new Exception("Failed to insert marks: " . $insert_stmt->error);
                }
            }
        }

        $mysqli->commit();
        $_SESSION['success'] = "Marks uploaded successfully!";
    } else {
        $_SESSION['failed'] = "No file uploaded";
    }
} catch (Exception $e) {
    $mysqli->rollback();
    $_SESSION['failed'] = "Error: " . $e->getMessage();
}

header("Location: " . $_SERVER['HTTP_REFERER']);
exit();