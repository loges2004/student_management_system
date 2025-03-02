<?php
include('db.php'); // Include database connection

// Handle Update
if (isset($_POST['update'])) {
    $register_no = $_POST['register_no'];
    $student_name = $_POST['student_name'];
    $roll_no = $_POST['roll_no'];
    $gender = $_POST['gender'];
    $dob = $_POST['dob'];
    $blood_group = $_POST['blood_group'];
    $address = $_POST['address'];
    $phone_no = $_POST['phone_no'];
    $admission_type = $_POST['admission_type'];
    $first_graduate = $_POST['first_graduate'];
    $day_scholar_hosteller = $_POST['day_scholar_hosteller'];
    $father_name = $_POST['father_name'];
    $father_occupation = $_POST['father_occupation'];
    $mother_name = $_POST['mother_name'];
    $mother_occupation = $_POST['mother_occupation'];
    $parent_number = $_POST['parent_number'];
    $tenth_passed_year = $_POST['tenth_passed_year'];
    $tenth_percentage = $_POST['tenth_percentage'];
    $twelfth_passed_year = $_POST['twelfth_passed_year'];
    $twelfth_percentage = $_POST['twelfth_percentage'];
    $aadhaar_no = $_POST['aadhaar_no'];
    $pan_no = $_POST['pan_no'];
    $caste = $_POST['caste'];
    $religion = $_POST['religion'];
    $nationality = $_POST['nationality'];
    $mother_tongue = $_POST['mother_tongue'];
    $emis_no = $_POST['emis_no'];
    $username = $_POST['username'];
    $department = $_POST['department'];
    $section = $_POST['section'];

    // Use prepared statements to prevent SQL injection
    $query = "UPDATE stud SET 
              student_name=?, roll_no=?, gender=?, 
              dob=?, blood_group=?, address=?, 
              phone_no=?, admission_type=?, 
              first_graduate=?, day_scholar_hosteller=?, 
              father_name=?, father_occupation=?, 
              mother_name=?, mother_occupation=?, 
              parent_number=?, tenth_passed_year=?, 
              tenth_percentage=?, twelfth_passed_year=?, 
              twelfth_percentage=?, aadhaar_no=?, 
              pan_no=?, caste=?, religion=?, 
              nationality=?, mother_tongue=?, 
              emis_no=?, username=?, department=?, 
              section=? 
              WHERE register_no=?";

    $stmt = $mysqli->prepare($query);
    if (!$stmt) {
        die("SQL Error: " . $mysqli->error);
    }

    // Bind parameters (30 parameters total)
    $stmt->bind_param(
        "sssssssssssssssssssssssssssss",
        $student_name, $roll_no, $gender,
        $dob, $blood_group, $address,
        $phone_no, $admission_type,
        $first_graduate, $day_scholar_hosteller,
        $father_name, $father_occupation,
        $mother_name, $mother_occupation,
        $parent_number, $tenth_passed_year,
        $tenth_percentage, $twelfth_passed_year,
        $twelfth_percentage, $aadhaar_no,
        $pan_no, $caste, $religion,
        $nationality, $mother_tongue,
        $emis_no, $username, $department,
        $section, $register_no // WHERE clause parameter
    );

    // Execute the query
    if ($stmt->execute()) {
        echo "<script>
                Swal.fire('Success!', 'Student details updated!', 'success')
                    .then(() => window.location.href = 'student_entry.php');
              </script>";
    } else {
        echo "<script>Swal.fire('Error!', 'Update failed.', 'error');</script>";
    }

    $stmt->close();
}

$query = "SELECT * FROM stud";
$result = $mysqli->query($query);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Management</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .table-responsive {
            margin-top: 20px;
        }
        .action-buttons {
            display: flex;
            gap: 5px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2 class="my-4">Student Management</h2>
         
        <!-- Search Bar -->
        <div class="mb-3">
            <input type="text" id="searchStudent" class="form-control" placeholder="Search by Student Name or Register No">
        </div>


        <!-- Student Table -->
        <div class="table-responsive">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Profile Image</th>
                        <th>Register No</th>
                        <th>Student Name</th>
                        <th>Roll No</th>
                        <th>Gender</th>
                        <th>Date of Birth</th>
                        <th>Blood Group</th>
                        <th>Address</th>
                        <th>Phone No</th>
                        <th>Admission Type</th>
                        <th>First Graduate</th>
                        <th>Day Scholar/Hosteller</th>
                        <th>Father's Name</th>
                        <th>Father's Occupation</th>
                        <th>Mother's Name</th>
                        <th>Mother's Occupation</th>
                        <th>Parent's Contact</th>
                        <th>10th Passed Year</th>
                        <th>10th Percentage</th>
                        <th>12th Passed Year</th>
                        <th>12th Percentage</th>
                        <th>Aadhaar No</th>
                        <th>PAN No</th>
                        <th>Caste</th>
                        <th>Religion</th>
                        <th>Nationality</th>
                        <th>Mother Tongue</th>
                        <th>EMIS No</th>
                        <th>Username</th>
                        <th>Department</th>
                        <th>Section</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <form method="POST" action="">
                            <td><img src="<?= $row['profile_image'] ?>" width="50" height="50"></td>
                                <td><?= $row['register_no'] ?></td>
                                <td><input type="text" name="student_name" value="<?= $row['student_name'] ?>" disabled></td>
                                <td><input type="text" name="roll_no" value="<?= $row['roll_no'] ?>" disabled></td>
                                <td><input type="text" name="gender" value="<?= $row['gender'] ?>" disabled></td>
                                <td><input type="date" name="dob" value="<?= $row['dob'] ?>" disabled></td>
                                <td><input type="text" name="blood_group" value="<?= $row['blood_group'] ?>" disabled></td>
                                <td><input type="text" name="address" value="<?= $row['address'] ?>" disabled></td>
                                <td><input type="text" name="phone_no" value="<?= $row['phone_no'] ?>" disabled></td>
                                <td><input type="text" name="admission_type" value="<?= $row['admission_type'] ?>" disabled></td>
                                <td><input type="text" name="first_graduate" value="<?= $row['first_graduate'] ?>" disabled></td>
                                <td><input type="text" name="day_scholar_hosteller" value="<?= $row['day_scholar_hosteller'] ?>" disabled></td>
                                <td><input type="text" name="father_name" value="<?= $row['father_name'] ?>" disabled></td>
                                <td><input type="text" name="father_occupation" value="<?= $row['father_occupation'] ?>" disabled></td>
                                <td><input type="text" name="mother_name" value="<?= $row['mother_name'] ?>" disabled></td>
                                <td><input type="text" name="mother_occupation" value="<?= $row['mother_occupation'] ?>" disabled></td>
                                <td><input type="text" name="parent_number" value="<?= $row['parent_number'] ?>" disabled></td>
                                <td><input type="text" name="tenth_passed_year" value="<?= $row['tenth_passed_year'] ?>" disabled></td>
                                <td><input type="text" name="tenth_percentage" value="<?= $row['tenth_percentage'] ?>" disabled></td>
                                <td><input type="text" name="twelfth_passed_year" value="<?= $row['twelfth_passed_year'] ?>" disabled></td>
                                <td><input type="text" name="twelfth_percentage" value="<?= $row['twelfth_percentage'] ?>" disabled></td>
                                <td><input type="text" name="aadhaar_no" value="<?= $row['aadhaar_no'] ?>" disabled></td>
                                <td><input type="text" name="pan_no" value="<?= $row['pan_no'] ?>" disabled></td>
                                <td><input type="text" name="caste" value="<?= $row['caste'] ?>" disabled></td>
                                <td><input type="text" name="religion" value="<?= $row['religion'] ?>" disabled></td>
                                <td><input type="text" name="nationality" value="<?= $row['nationality'] ?>" disabled></td>
                                <td><input type="text" name="mother_tongue" value="<?= $row['mother_tongue'] ?>" disabled></td>
                                <td><input type="text" name="emis_no" value="<?= $row['emis_no'] ?>" disabled></td>
                                <td><input type="text" name="username" value="<?= $row['username'] ?>" disabled></td>
                                <td><input type="text" name="department" value="<?= $row['department'] ?>" disabled></td>
                                <td><input type="text" name="section" value="<?= $row['section'] ?>" disabled></td>
                                <td class="action-buttons">
                                    <input type="hidden" name="register_no" value="<?= $row['register_no'] ?>">
                                    <button type="button" class="btn btn-warning btn-sm edit-btn">Edit</button>
                                    <button type="submit" name="update" class="btn btn-success btn-sm update-btn" style="display: none;">Update</button>
                                    <button type="button" class="btn btn-danger btn-sm delete-btn" data-id="<?= $row['register_no'] ?>">Delete</button>
                                </td>
                            </form>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
   document.getElementById('searchStudent').addEventListener('input', function() {
    const searchValue = this.value.toLowerCase().trim(); // Get the search value and convert to lowercase
    const rows = document.querySelectorAll('tbody tr'); // Get all table rows

    rows.forEach(row => {
        // Get the student name and register number from the input fields
        const studentName = row.querySelector('input[name="student_name"]')?.value.toLowerCase() || '';
        const registerNo = row.querySelector('input[name="register_no"]')?.value.toLowerCase() || '';

        // Check if the search value matches either the student name or register number
        if (studentName.includes(searchValue) || registerNo.includes(searchValue)) {
            row.style.display = ''; // Show the row
        } else {
            row.style.display = 'none'; // Hide the row
        }
    });
});


        // Delete Student
        document.querySelectorAll('.delete-btn').forEach(button => {
            button.addEventListener('click', function() {
                const registerNo = this.getAttribute('data-id');
                Swal.fire({
                    title: 'Are you sure?',
                    text: 'You will not be able to recover this student record!',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Yes, delete it!',
                    cancelButtonText: 'No, cancel!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = `delete_student.php?register_No=${registerNo}`;
                    }
                });
            });
        });
        // Enable Edit Mode
        document.querySelectorAll('.edit-btn').forEach(button => {
            button.addEventListener('click', function() {
                const row = this.closest('tr');
                row.querySelectorAll('input').forEach(input => {
                    input.disabled = false;
                });
                this.style.display = 'none';
                row.querySelector('.update-btn').style.display = 'inline-block';
            });
        });
    </script>
</body>
</html>