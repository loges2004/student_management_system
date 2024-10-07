<?php
session_start();
require 'db.php'; // Ensure this file contains your database connection

// Check if year, semester, and department are set
if (isset($_POST['year']) && isset($_POST['semester']) && isset($_POST['department'])) {
    $year = $_POST['year'];
    $semester = $_POST['semester'];
    $department = $_POST['department'];

    // Fetch subjects for the selected semester and department
    $stmt = $mysqli->prepare("SELECT subject_id, subject_name, subject_code FROM subjects WHERE semester = ? AND department = ?");
    if ($stmt === false) {
        die('MySQL prepare error: ' . $mysqli->error);
    }

    $stmt->bind_param('ss', $semester, $department);
    $stmt->execute();
    $subjects = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

    // Check if subjects are found
    if (empty($subjects)) {
        $_SESSION['error'] = "No subjects found for the selected year, semester, and department.";
        header("Location: dashboard.php");
        exit();
    }
} else {
    die('Year, Semester, or Department not set');
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <title>Enter Grades</title>
    <style>
    body {
    background-color: #f8f9fa;
}
.container {
    max-width: 900px;
    background-color: #ffffff;
    border-radius: 8px;
    padding: 30px;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
}
h2 {
    margin-bottom: 30px;
    color: #343a40;
}
.form-control {
    height: 35px; /* Uniform height */
    padding: 5px 10px; /* Uniform padding */
    font-size: 14px; /* Uniform font size */
    width: 100%; /* Ensure full width for all inputs */
    margin-bottom: 10px;
}

.btn {
    width: 100%;
}
table thead {
    background-color: #007bff;
    color: #ffffff;
}
table tbody tr:nth-child(even) {
    background-color: #e9ecef;
}
table tbody tr:nth-child(odd) {
    background-color: #f2f2f2;
}
.profile-image {
    width: 100px;
    height: 100px;
    border-radius: 50%;
    object-fit: cover;
    
}
.image-container {
    display: flex;
    align-items: center;
    justify-content: flex-end;
    margin-bottom: 0; /* Removes the bottom margin */
    margin-top: -30px; /* Adjust this value to move the image upwards */
}

.image-container img {
    margin-left: 15px; /* Space between image and fields */
}
</style>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
    <div class="container mt-5">
        <h2 class="text-center">Enter Grades for Semester <?= htmlspecialchars($semester); ?></h2>
        <form method="POST" action="save_grades.php" id="gradesForm">
            <input type="hidden" name="year" value="<?= htmlspecialchars($year); ?>">
            <input type="hidden" name="semester" value="<?= htmlspecialchars($semester); ?>">

            <div class="form-group">
                <label for="register_no" class="form-label">Register No</label>
                <div class="d-flex">
                    <input type="text" class="form-control" id="register_no" name="register_no" required>
                    <div class="image-container">
                        <img id="profile_image" src="path/to/default/image.jpg" class="profile-image" alt="Profile Image">
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label for="student_name" class="form-label">Student Name</label>
                <div class="d-flex">
                <input type="text" class="form-control" id="student_name" name="student_name" required readonly>
            </div>

            <div class="form-group">
                <label for="department" class="form-label">Department</label>
                <input type="text" class="form-control" id="department" name="department" required readonly>
            </div>

            <table class="table table-bordered mt-3">
                <thead>
                    <tr>
                        <th>Subject Code</th>
                        <th>Subject Name</th>
                        <th>Grade</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($subjects as $subject): ?>
                        <tr>
                            <td><?= htmlspecialchars($subject['subject_code']); ?></td>
                            <td><?= htmlspecialchars($subject['subject_name']); ?></td>
                            <td>
                                <input type="text" class="form-control" name="grades[<?= $subject['subject_id']; ?>]" required>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

            <button type="submit" class="btn btn-primary">Save Grades</button>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
    $(document).ready(function() {
        $('#gradesForm').on('submit', function(event) {
            event.preventDefault();

            // Show a confirmation dialog
            Swal.fire({
                title: 'Are you sure?',
                text: "Do you want to save these grades?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, save it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    this.submit();
                }
            });
        });

        $('#register_no').on('change', function() {
            const registerNo = $(this).val();
            $.ajax({
                url: 'fetch_student.php', // Endpoint to fetch student details
                type: 'POST',
                data: { register_no: registerNo },
                success: function(data) {
                    const student = JSON.parse(data);
                    if (student) {
                        // Update fields with student data
                        $('#student_name').val(student.student_name);
                        $('#department').val(student.department);
                        $('#profile_image').attr('src', student.profile_image || 'path/to/default/image.jpg'); // Use the image URL from DB
                    } else {
                        // Show alert if student not found
                        Swal.fire({
                            icon: 'error',
                            title: 'Not Found',
                            text: 'No student found with this register number. Please check and try again.',
                        });
                        // Reset fields
                        $('#student_name').val('');
                        $('#department').val('');
                        $('#profile_image').attr('src', 'path/to/default/image.jpg'); // Reset image
                    }
                },
                error: function() {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Could not fetch student data. Please try again later.',
                    });
                    // Reset image on error
                    $('#profile_image').attr('src', 'path/to/default/image.jpg'); // Reset image
                }
            });
        });
    });
    </script>
</body>
</html>
