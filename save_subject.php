<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
<?php
// Database connection
include("db.php");


// Get form data and convert to uppercase
$subject_code = strtoupper($_POST['subject_code']);
$subject_name = strtoupper($_POST['subject_name']);
$department = strtoupper($_POST['department']);
$year = $_POST['year'];
$semester = $_POST['semester'];
$type = strtoupper($_POST['type']);
$credit = $_POST['credit'];
$total_hours = $_POST['total_hours'];
$sub_type = strtoupper($_POST['sub_type']);

// Check if the subject already exists based on subject_code
$query = "SELECT * FROM subjects WHERE subject_code = ?";
$stmt = $mysqli->prepare($query);

if (!$stmt) {
    die("SQL Error: " . $mysqli->error);
}

$stmt->bind_param("s", $subject_code);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    // Update existing record
    $update_query = "UPDATE subjects 
                     SET subject_name = ?, department = ?, years = ?, semester = ?, type = ?, credit_points = ?, total_hours = ?, sub_type = ? 
                     WHERE subject_code = ?";
    $stmt = $mysqli->prepare($update_query);

    if (!$stmt) {
        die("SQL Error: " . $mysqli->error);
    }

    $stmt->bind_param("ssisssiss", $subject_name, $department, $year, $semester, $type, $credit, $total_hours, $sub_type, $subject_code);
} else {
    // Insert new record
    $insert_query = "INSERT INTO subjects (subject_code, subject_name, department, years, semester, type, credit_points, total_hours, sub_type) 
                     VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $mysqli->prepare($insert_query);

    if (!$stmt) {
        die("SQL Error: " . $mysqli->error);
    }

    $stmt->bind_param("ssssssiis", $subject_code, $subject_name, $department, $year, $semester, $type, $credit, $total_hours, $sub_type);
}

// Execute the query
if ($stmt->execute()) {
    echo "<script>
        Swal.fire({
            title: 'Success!',
            text: 'Subject saved successfully!',
            icon: 'success',
            confirmButtonText: 'OK'
        }).then(() => {
            window.location.href = 'subject_entry.php';
        });
    </script>";
} else {
    echo "<script>Swal.fire('Error!', 'Failed to save subject.', 'error');</script>";
}


$stmt->close();
$mysqli->close();
?>
</body>
</html>