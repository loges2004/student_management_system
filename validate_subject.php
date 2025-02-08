<?php
// Include your database connection
include('db.php');

// Start the session
session_start();   

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $year = $_POST['year'];
    $test_type = $_POST['test_type'];
    $semester = $_POST['semester'];
    $department = $_POST['department'];
    $section = $_POST['section'];
    $subject_name = $_POST['subject_name'];
    $subject_code = $_POST['subject_code'];
    $testmark = $_POST['testmark']; // Get the testmark value from POST
    $staff_name = $_POST['staff_name']; // Get the testmark value from POST
    $staff_id = $_POST['staff_id']; // Get the testmark value from POST
    
    // Store form data in session variables
    $_SESSION['year'] = $year;
    $_SESSION['test_type'] = $test_type;
    $_SESSION['semester'] = $semester;
    $_SESSION['department'] = $department;
    $_SESSION['section'] = $section;
    $_SESSION['subject_name'] = $subject_name;
    $_SESSION['subject_code'] = $subject_code;
    $_SESSION['testmark'] = $testmark;
    $_SESSION['staff_name'] = $staff_name;
    $_SESSION['staff_id'] = $staff_id;


    // Prepare the SQL statement to check if the subject already exists
    $stmt = $mysqli->prepare("SELECT * FROM subjects WHERE subject_name = ? AND subject_code = ? AND department = ? AND semester = ?");
    $stmt->bind_param("ssss", $subject_name, $subject_code, $department, $semester);

    // Execute the statement
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if any row exists
    if ($result->num_rows > 0) {
        // Subject exists, redirect based on testmark
        header("Location: table_page.php");
    } else {
        // Subject does not exist, show a message
        echo "<script>alert('Subject does not exist. You can insert it if needed.'); window.history.back();</script>";
        exit();
    }

    // Close the statement
    $stmt->close();

    // Close the database connection
    $mysqli->close();
}
?>
