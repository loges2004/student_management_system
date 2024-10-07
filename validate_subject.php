<?php
// Include your database connection
include('db.php');

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $year = $_POST['year'];
    $test_type = $_POST['test_type'];
    $semester = $_POST['semester'];
    $department = $_POST['department'];
    $subject_name = $_POST['subject_name'];
    $subject_code = $_POST['subject_code'];

    // Prepare the SQL statement to check if the subject already exists
    $stmt = $mysqli->prepare("SELECT * FROM subjects WHERE subject_name = ? AND subject_code = ? AND department = ? AND semester = ?");
    $stmt->bind_param("ssss", $subject_name, $subject_code, $department, $semester);

    // Execute the statement
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if any row exists
    if ($result->num_rows > 0) {
        // Subject exists, redirect to assessment_details.php
        header("Location: assessment_details.php?subject_name=" . urlencode($subject_name) . 
               "&subject_code=" . urlencode($subject_code) . 
               "&year=" . urlencode($year) . 
               "&test_type=" . urlencode($test_type) . 
               "&department=" . urlencode($department) . 
               "&semester=" . urlencode($semester));
        exit();
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
