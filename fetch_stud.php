<?php
// fetch_stud.php

// Include your database connection
include('db.php');

// Fetch and sanitize POST parameters
$register_no = $_POST['register_no'] ?? '';
$test_type = $_POST['test_type'] ?? '';

// Initialize response array
$response = [];

// Validate input
if ($register_no) {
    // Define allowed test types to prevent SQL injection
    $allowed_test_types = ['serialtest1', 'serialtest2'];

    if (in_array($test_type, $allowed_test_types)) {
        // Prepare the SQL query
        $sql = "
            SELECT 
                s.student_name,
                s.department,
                t.q1_marks, t.q2_marks, t.q3_marks, t.q4_marks, t.q5_marks,
                t.q6_marks, t.q7_marks, t.q8_marks, t.q9_marks, t.q10_marks,
                t.q11A_marks, t.q11B_marks, t.q12A_marks, t.q12B_marks,
                t.q13A_marks, t.q13B_marks
            FROM 
                stud s
            LEFT JOIN 
                $test_type t ON s.register_no = t.registration_no
            WHERE 
                s.register_no = ?";

        // Prepare the SQL statement
        if ($stmt = $mysqli->prepare($sql)) {
            // Bind parameters
            $stmt->bind_param("s", $register_no);
            // Execute the statement
            $stmt->execute();
            // Get the result
            $result = $stmt->get_result();

            // Fetch the student data
            if ($row = $result->fetch_assoc()) {
                $response = $row; // Return the student data
            }

            // Close the statement
            $stmt->close();
        } else {
            // Handle prepare() failure
            $response['error'] = "Database query failed: " . $mysqli->error;
        }
    } else {
        // Invalid test_type provided
        $response['error'] = "Invalid test type selected.";
    }
} else {
    $response['error'] = "No register number provided.";
}

// Return the JSON response
echo json_encode($response);
?>
