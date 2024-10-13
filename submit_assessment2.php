<?php
include('db.php'); // MySQLi connection

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
    // Store CO selections
    $co_questions = [];
    for ($i = 1; $i <= 16; $i++) {
        if ($i <= 10) {
            // Ensure the CO question exists before adding
            $co_key = "co_question_$i";
            $co_questions[] = isset($_POST[$co_key]) ? $_POST[$co_key] : '';
        } else {
            // For questions 11 to 16 with 'a' and 'b' suffixes
            $co_key_a = "co_question_{$i}a";
            $co_key_b = "co_question_{$i}b";
            $co_questions[] = isset($_POST[$co_key_a]) ? $_POST[$co_key_a] : '';
            $co_questions[] = isset($_POST[$co_key_b]) ? $_POST[$co_key_b] : '';
        }
    }

    // Verify the count of CO questions
    $expected_co_count = 22; // 10 (1-10) + 12 (11a, 11b,...,16a, 16b)
    if (count($co_questions) !== $expected_co_count) {
        die("Error: Expected $expected_co_count CO questions, but got " . count($co_questions) . ".");
    }

    // Prepare SQL statement
    $sql = "INSERT INTO course_outcomes100 (
                year, semester, department, test_type, subject_name, subject_code,
                co_question_1, co_question_2, co_question_3, co_question_4, co_question_5,
                co_question_6, co_question_7, co_question_8, co_question_9, co_question_10,
                co_question_11a, co_question_11b, co_question_12a, co_question_12b,
                co_question_13a, co_question_13b,
                co_question_14a, co_question_14b,
                co_question_15a, co_question_15b,
                co_question_16a, co_question_16b
            ) VALUES (
                ?, ?, ?, ?, ?, ?,
                ?, ?, ?, ?, ?,
                ?, ?, ?, ?, ?,
                ?, ?, ?, ?, ?,
                ?, ?, ?, ?, ?, ?,?
            )
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
                co_question_13b = VALUES(co_question_13b),
                co_question_14a = VALUES(co_question_14a),
                co_question_14b = VALUES(co_question_14b),
                co_question_15a = VALUES(co_question_15a),
                co_question_15b = VALUES(co_question_15b),
                co_question_16a = VALUES(co_question_16a),
                co_question_16b = VALUES(co_question_16b)";

    // Prepare SQL statement
    $stmt = $mysqli->prepare($sql);

    // Check if prepare() failed
    if ($stmt === false) {
        die('Prepare failed: ' . htmlspecialchars($mysqli->error)); // Output the SQL error message
    }

    // Create a type string for binding parameters
    // 6 main fields + 22 CO questions = 28 's'
    $types = str_repeat("s", 6) . str_repeat("s", $expected_co_count);
    
    // Combine all parameters into one array
    $params = array_merge([$year, $semester, $department, $test_type, $subject_name, $subject_code], $co_questions);
    
    // Debugging: Print counts and parameters
    $expected_count = 28;
    $actual_count = count($params);
    
    if ($actual_count !== $expected_count) {
        die("Error: Expected $expected_count parameters, but got $actual_count. Check your input fields.");
    }

    // Bind parameters for the prepared statement
    if (!$stmt->bind_param($types, ...$params)) {
        die('Bind param failed: ' . htmlspecialchars($stmt->error));
    }
    
    // Execute the statement
    if ($stmt->execute()) {
        echo "<script>
            alert('Assessment details saved successfully!');
            window.location.href='enter_test2.php?year=" . urlencode($year) . "&semester=" . urlencode($semester) . "&department=" . urlencode($department) . "&test_type=" . urlencode($test_type) . "&subject_name=" . urlencode($subject_name) . "&subject_code=" . urlencode($subject_code) . "';
            </script>";
    } else {
        // Show detailed error message
        echo "<script>alert('Error saving assessment details: " . addslashes($stmt->error) . "');</script>";
    }
    
    // Close the statement
    $stmt->close();
} else {
    // Redirect if not POST
    header("Location: assessment_details2.php");
    exit();
}

// Close the database connection
$mysqli->close();
?>
