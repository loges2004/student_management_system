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
// Include your database connection
include('db.php');

// Start the session
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $year = $_POST['year'];
    $test_type = $_POST['test_type'];
    $semester = $_POST['semester'];
    $department = $_POST['department'];
    $section = $_POST['section'];
    $subject_name = $_POST['subject_name'];
    $subject_code = $_POST['subject_code'];
    $testmark = $_POST['testmark'];
    $staff_name = trim(strtoupper($_POST['staff_name'])); // Trim and convert to uppercase
    $staff_id = trim($_POST['staff_id']);
    $regulation = strtoupper($_POST['regulation']);

    // Debugging: Print the inputs
    echo "<script>console.log('Input Staff Name:', '" . $staff_name . "');</script>";
    echo "<script>console.log('Input Staff ID:', '" . $staff_id . "');</script>";

    // ✅ Validate staff_id and staff_name
   // ✅ Validate staff_id and staff_name
$stmt_staff = $mysqli->prepare("
SELECT * 
FROM staff 
WHERE REPLACE(UPPER(TRIM(staff_name)), '  ', ' ') = REPLACE(UPPER(TRIM(?)), '  ', ' ') 
AND staff_id = ?
");

$stmt_staff->bind_param("ss", $staff_name, $staff_id);
$stmt_staff->execute();
$result_staff = $stmt_staff->get_result();

if ($result_staff->num_rows === 0) {
// Debugging: Print the database values for better debugging
$debug_stmt = $mysqli->prepare("SELECT staff_name, staff_id FROM staff WHERE staff_id = ?");
$debug_stmt->bind_param("s", $staff_id);
$debug_stmt->execute();
$result_debug = $debug_stmt->get_result();

$debug_stmt->close();

echo "<script>
    Swal.fire({
        title: 'Error!',
        text: 'Invalid staff ID or name. Please check your credentials.',
        icon: 'error'
    }).then(() => {
        window.history.back();
    });
</script>";
$stmt_staff->close();
$mysqli->close();
exit;
} else {
// Debugging: Output matched staff details
$row = $result_staff->fetch_assoc();

}


    // ✅ Store form data in session variables
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
    $_SESSION['regulation'] = $regulation;

    // ✅ Check if the subject already exists
    $stmt = $mysqli->prepare("SELECT * FROM subjects WHERE UPPER(subject_name) = UPPER(?) AND subject_code = ? AND department = ? AND semester = ? AND regulation = ?");
    $stmt->bind_param("sssss", $subject_name, $subject_code, $department, $semester, $regulation);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // ✅ Subject exists, show a success message
        echo "<script>
            Swal.fire({
                title: 'Success!',
                text: 'Subject found! Redirecting...',
                icon: 'success'
            }).then(() => {
                window.location.href = 'table_page.php';
            });
        </script>";
    } else {
        // ❌ Subject does not exist
        echo "<script>
            Swal.fire({
                title: 'Error!',
                text: 'Subject does not exist. You can insert it if needed.',
                icon: 'error'
            }).then(() => {
                window.history.back();
            });
        </script>";
    }

    // ✅ Close statements and connection
    $stmt->close();
    $stmt_staff->close();
    $mysqli->close();
}
?>

</body>
</html>