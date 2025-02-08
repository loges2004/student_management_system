<?php
// Include your database connection
include('db.php');

// Start the session
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get form data
    $year = $_POST['year'];
    $semester = $_POST['semester'];
    $department = $_POST['department'];
    $test_type = $_POST['test_type'];
    $subject_name = $_POST['subject_name'];
    $subject_code = $_POST['subject_code'];
    
    // Check if required fields are empty
    if (empty($year) || empty($semester) || empty($department) || empty($test_type) || empty($subject_name) || empty($subject_code)) {
        echo "<script>alert('Please fill in all required fields.'); window.history.back();</script>";
        exit();
    }

    // Store form data in session variables
    $_SESSION['year'] = $year;
    $_SESSION['semester'] = $semester;
    $_SESSION['department'] = $department;
    $_SESSION['test_type'] = $test_type;
    $_SESSION['subject_name'] = $subject_name;
    $_SESSION['subject_code'] = $subject_code;

    // Store CO selections
    $co_questions = [];
    for ($i = 1; $i <= 13; $i++) {
        if ($i <= 10) {
            $co_questions[] = $_POST["co_question_$i"];
        } else {
            $co_questions[] = $_POST["co_question_{$i}a"];
            $co_questions[] = $_POST["co_question_{$i}b "];
        }
    }

    // Prepare SQL statement
    if ($test_type === 'serialtest2') {
        $stmt = $mysqli->prepare("INSERT INTO course_outcomes (year, semester, department, test_type, subject_name, subject_code,
            co_question_1, co_question_2, co_question_3, co_question_4, co_question_5,
            co_question_6, co_question_7, co_question_8, co_question_9, co_question_10,
            co_question_11a, co_question_11b, co_question_12a, co_question_12b,
            co_question_13a, co_question_13b) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
               ON DUPLICATE KEY UPDATE
            co_question_1 = VALUES(co_question_1),
            co_question_2 = VALUES(co_question_2),
            co_question_3 = VALUES(co_question_3),
            co_question_4 = VALUES(co_question_4),
            co_question_5 = VALUES(co_question_5),
            co_question_6 = VALUES(co_question_6),
            co_question_7 = VALUES(co_question_7),
            co_question_8 = VALUES(co_question_8),
            co_question_9 = VALUES(co_question_9),
            co_question_10 = VALUES(co_question_10),
            co_question_11a = VALUES(co_question_11a),
            co_question_11b = VALUES(co_question_11b),
            co_question_12a = VALUES(co_question_12a),
            co_question_12b = VALUES(co_question_12b),
            co_question_13a = VALUES(co_question_13a),
            co_question_13b = VALUES(co_question_13b)");
    } else {
        $stmt = $mysqli->prepare("INSERT INTO course_outcomes (year, semester, department, test_type, subject_name, subject_code,
            co_question_1, co_question_2, co_question_3, co_question_4, co_question_5,
            co_question_6, co_question_7, co_question_8, co_question_9, co_question_10,
            co_question_11a, co_question_11b, co_question_12a, co_question_12b,
            co_question_13a, co_question_13b) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
            ON DUPLICATE KEY UPDATE
            co_question_1 = VALUES(co_question_1),
            co_question_2 = VALUES(co_question_2),
            co_question_3 = VALUES(co_question_3),
            co_question_4 = VALUES(co_question_4),
            co_question_5 = VALUES(co_question_5),
            co_question_6 = VALUES(co_question_6),
            co_question_7 = VALUES(co_question_7),
            co_question_8 = VALUES(co_question_8),
            co_question_9 = VALUES(co_question_9),
            co_question_10 = VALUES(co_question_10),
            co_question_11a = VALUES(co_question_11a),
            co_question_11b = VALUES(co_question_11b),
            co_question_12a = VALUES(co_question_12a),
            co_question_12b = VALUES(co_question_12b),
            co_question_13a = VALUES(co_question_13a),
            co_question_13b = VALUES(co_question_13b)");
    }

    // Create a type string for binding parameters
    $types = str_repeat("s", 6) . str_repeat("s", count($co_questions));
    
    // Combine all parameters into one array
    $params = array_merge([$year, $semester, $department, $test_type, $subject_name, $subject_code], $co_questions);
    
    // Print parameters for debugging
  

    // Bind parameters for the prepared statement
    $stmt->bind_param($types, ...$params);
    
    // Execute the statement
    if ($stmt->execute()) {
        echo "<script>
        alert('Assessment details saved successfully!');
        window.location.href='enter_test.php?year=" . urlencode($year) . "&semester=" . urlencode($semester) . "&department=" . urlencode($department) . "&test_type=" . urlencode($test_type) . "&subject_name=" . urlencode($subject_name) . "&subject_code=" . urlencode($subject_code) . "';</script>";
    } else {
        // Show detailed error message
        echo "<script>alert('Error saving assessment details: " . $stmt->error . "');</script>";
    }
    
    // Close the statement
    $stmt->close();
} else {
    // Redirect if not POST
    header("Location: assessment_details.php");
    exit();
}

// Close the database connection
$mysqli->close();
?>
