
<?php
session_start();
include('db.php');

function toUpper($value) {
    return mb_strtoupper(trim($value), 'UTF-8');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
       // Retrieve and sanitize form data
       $departmentId = toUpper($_POST['departmentId'] ?? '');
       $programType = toUpper($_POST['programType'] ?? '');
       $degreeType = toUpper($_POST['degreeType'] ?? '');
       $departmentName = toUpper($_POST['departmentName'] ?? '');
       $year = (int)($_POST['year'] ?? 0);

       // Validate required fields
       if (empty($departmentId) || empty($programType) || empty($degreeType) || 
           empty($departmentName) || empty($year)) {
           throw new Exception("All fields are required.");
       }

      // Check if the department already exists
$check_stmt = $mysqli->prepare("
SELECT COUNT(*) FROM departments WHERE department_id = ? 
AND program_type = ? AND degree_type = ? AND department_name = ? AND year = ?
");
$check_stmt->bind_param("ssssi", $departmentId, $programType, $degreeType, $departmentName, $year);
$check_stmt->execute();
$check_stmt->bind_result($count);
$check_stmt->fetch();
$check_stmt->close();

if ($count > 0) {
$_SESSION['failed'] = "Duplicate entry: This department already exists!";
header("Location: department_entry.php");
exit();
}

         // Insert data into the database
         $stmt = $mysqli->prepare("
         INSERT INTO departments (
             department_id, program_type, degree_type, department_name, year
         ) VALUES (?, ?, ?, ?, ?)
     ");
        $stmt->bind_param("ssssi", 
        $departmentId,
        $programType,
        $degreeType,
        $departmentName,
        $year
    );

        if (!$stmt->execute()) {
            throw new Exception("Database error: " . $stmt->error);
        }

        $_SESSION['success'] = "Department created successfully!";
        header("Location: department_entry.php");
        exit();

    } catch (Exception $e) {
        $_SESSION['failed'] = "Error: " . $e->getMessage();
        header("Location: department_entry.php");
        exit();
    }
}

?>