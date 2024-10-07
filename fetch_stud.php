<?php
// fetch_stud.php
include('db.php');

// Get register number from AJAX request
if (isset($_POST['register_no'])) {
    $register_no = $_POST['register_no'];
    
    // Query to fetch student details
    $sql = "SELECT student_name, department FROM stud WHERE register_no = ?";
    $stmt = $mysqli->prepare($sql);
    $stmt->bind_param("s", $register_no);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $student = $result->fetch_assoc();
        echo json_encode($student); // Return the student details as JSON
    } else {
        echo json_encode(null); // Student not found
    }
    $stmt->close();
}
$mysqli->close();
?>