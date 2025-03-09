<?php
include('db.php');
session_start();

error_reporting(E_ALL);
ini_set('display_errors', 1);

// Validate session variables
$required_session = ['year', 'semester', 'department', 'section', 'test_type', 'subject_name', 'subject_code', 'testmark', 'regulation'];
foreach ($required_session as $var) {
    if (!isset($_SESSION[$var])) {
        die("Session expired or invalid. Please reconfigure the test.");
    }
}

require 'vendor/autoload.php';
use PhpOffice\PhpSpreadsheet\IOFactory;

// Function to get CO
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
        $marksArray = json_decode($_POST['marks'], true);
        $countsArray = json_decode($_POST['counts'], true);

        // Get or create test_id
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

        if (!$test) {
            // Insert new test entry
            $insertStmt = $mysqli->prepare("
                INSERT INTO test_results (
                    staffname, year, semester, department, 
                    section, test_type, testmark, subject_name, subject_code, staff_id, regulation
                ) VALUES (UPPER(?), ?, ?, ?, ?, ?, ?, UPPER(?), UPPER(?), ?, UPPER(?))
            ");
            $staffname = $_SESSION['staffname'] ?? '';
            $staff_id = $_SESSION['staff_id'] ?? 0;
            $regulation = $_SESSION['regulation'] ?? '';
            $insertStmt->bind_param(
                "siisssissis",
                $staffname,
                $_SESSION['year'],
                $_SESSION['semester'],
                $_SESSION['department'],
                $_SESSION['section'],
                $_SESSION['test_type'],
                $_SESSION['testmark'],
                $_SESSION['subject_name'],
                $_SESSION['subject_code'],
                $staff_id,
                $regulation
            );
            $insertStmt->execute();
            $test_id = $mysqli->insert_id;
        } else {
            $test_id = $test['id'];
        }

        // Fetch CO and Bloom's Taxonomy mapping
        $co_mapping = [];
        $blooms_mapping = [];
        $co_stmt = $mysqli->prepare("SELECT question_number, course_outcome, blooms_taxonomy FROM co_questions WHERE test_id = ?");
        $co_stmt->bind_param("i", $test_id);
        $co_stmt->execute();
        $co_result = $co_stmt->get_result();
        while ($row = $co_result->fetch_assoc()) {
            $co_mapping[$row['question_number']] = strtoupper($row['course_outcome']);
            $blooms_mapping[$row['question_number']] = strtoupper($row['blooms_taxonomy']);
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
            $student_name = strtoupper($row[1]);
            $total_mark = array_pop($row);
            $marks = array_slice($row, 2, $questionCount);

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

            // Insert/update marks
            for ($i = 0; $i < $questionCount; $i++) {
                $question_number = $i + 1;
                $maxMark = $marksArray[$i] ?? 0;
                $mark = $marks[$i];
                $attended = $mark > 0 ? 1 : 0;
                $co = $co_mapping[$question_number] ?? strtoupper(getCourseOutcome($question_number, $countsArray));
                $blooms_taxonomy = $blooms_mapping[$question_number] ?? 'BL1-Remembering'; // Default Bloom's Taxonomy
                $attendance = ($total_mark > 0) ? 'PRESENT' : 'ABSENT';

                $insert_stmt = $mysqli->prepare("
                    INSERT INTO student_marks (
                        test_id, register_no, question_number, 
                        year, department, semester, student_id, student_name, section,
                        marks, attended, course_outcome, blooms_taxonomy,
                        test_type, testmark, subject_code, subject_name, attendance, total_marks, regulation
                    ) VALUES (
                        ?, ?, ?, 
                        ?, ?, ?, ?, ?, ?,
                        ?, ?, ?, ?,
                        ?, ?, ?, ?, ?, ?, ?
                    )
                    ON DUPLICATE KEY UPDATE
                        marks = VALUES(marks),
                        attended = VALUES(attended),
                        attendance = VALUES(attendance),
                        section = VALUES(section),
                        total_marks = VALUES(total_marks),
                        regulation = VALUES(regulation)
                ");

                $insert_stmt->bind_param(
                    "isiiissssiisssssssis",
                    $test_id,
                    $register_no,
                    $question_number,
                    $_SESSION['year'],
                    $_SESSION['department'],
                    $_SESSION['semester'],
                    $student_id,
                    $student_name,
                    $_SESSION['section'],
                    $mark,
                    $attended,
                    $co,
                    $blooms_taxonomy,
                    $_SESSION['test_type'],
                    $_SESSION['testmark'],
                    $_SESSION['subject_code'],
                    $_SESSION['subject_name'],
                    $attendance,
                    $total_mark,
                    $_SESSION['regulation']
                );

                if (!$insert_stmt->execute()) {
                    throw new Exception("Failed to insert/update marks: " . $insert_stmt->error);
                }
            }
        }

        $mysqli->commit();
        $_SESSION['success'] = "Marks uploaded/updated successfully!";
    } else {
        $_SESSION['failed'] = "No file uploaded";
    }
} catch (Exception $e) {
    $mysqli->rollback();
    $_SESSION['failed'] = "Error: " . $e->getMessage();
}

header("Location: " . $_SERVER['HTTP_REFERER']);
exit();
?>