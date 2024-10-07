<?php
session_start();
require 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $register_no = $_POST['register_no'];
    $profile_image = '';

    // Handle the file upload
    if (isset($_FILES['profile_image']) && $_FILES['profile_image']['error'] == UPLOAD_ERR_OK) {
        $uploadDir = 'uploads/'; // Ensure this directory exists and is writable
        $imageName = basename($_FILES['profile_image']['name']);
        $uploadFile = $uploadDir . $imageName;

        // Check if the upload directory exists
        if (!is_dir($uploadDir)) {
            die('Upload directory does not exist. Please create the "uploads" directory.');
        }

        // Move the uploaded file to the desired directory
        if (move_uploaded_file($_FILES['profile_image']['tmp_name'], $uploadFile)) {
            $profile_image = $uploadFile; // Save the file path
        } else {
            die('Error uploading file. Check if the directory is writable.');
        }
    } else {
        die('File upload error. Please check the file and try again.');
    }

    // Fetch student by register number
    $stmt = $mysqli->prepare("SELECT student_id, student_name, years, department FROM stud WHERE register_no = ?");
    if ($stmt === false) {
        die('MySQL prepare error: ' . $mysqli->error);
    }

    $stmt->bind_param('s', $register_no);
    $stmt->execute();
    $student = $stmt->get_result()->fetch_assoc();

    if (!$student) {
        die("Student not found.");
    }

    $student_id = $student['student_id'];
    $student_name = $student['student_name'];
    $year = $student['years'];
    $department = $student['department'];

    // Update the profile image path in the database
    $stmt = $mysqli->prepare("UPDATE stud SET profile_image = ? WHERE student_id = ?");
    if ($stmt === false) {
        die('MySQL prepare error: ' . $mysqli->error);
    }

    $stmt->bind_param('si', $profile_image, $student_id);
    if (!$stmt->execute()) {
        die('MySQL execute error: ' . $stmt->error);
    }

    $stmt->close();
    $mysqli->close();

    echo "Profile image uploaded successfully.";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Upload Profile Image</title>
    <style>
        body {
            background-color: #f8f9fa;
        }
        .container {
            max-width: 600px;
            background-color: #ffffff;
            border-radius: 8px;
            padding: 20px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            margin-top: 50px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2 class="text-center">Upload Profile Image</h2>
        <form method="POST" action="" enctype="multipart/form-data">
            <div class="mb-3">
                <label for="register_no" class="form-label">Enter Register Number</label>
                <input type="text" class="form-control" id="register_no" name="register_no" required>
            </div>
            <div class="mb-3">
                <label for="profile_image" class="form-label">Upload Profile Image</label>
                <input type="file" class="form-control" id="profile_image" name="profile_image" accept="image/*">
            </div>
            <button type="submit" class="btn btn-primary">Submit</button>
        </form>
    </div>
</body>
</html>
