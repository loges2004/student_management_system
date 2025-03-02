<?php
include('db.php'); // Include database connection

// Handle Update
if (isset($_POST['update'])) {
    $staff_id = $_POST['staff_id'];
    $first_name = $_POST['first_name'];
    $middle_name = $_POST['middle_name'];
    $last_name = $_POST['last_name'];
    $date_of_birth = $_POST['date_of_birth'];
    $gender = $_POST['gender'];
    $blood_group = $_POST['blood_group'];
    $caste = $_POST['caste'];
    $religion = $_POST['religion'];
    $phone_number = $_POST['phone_number'];
    $alternate_phone_number = $_POST['alternate_phone_number'];
    $email = $_POST['email'];
    $alternate_email = $_POST['alternate_email'];
    $address = $_POST['address'];
    $city = $_POST['city'];
    $state = $_POST['state'];
    $zip_code = $_POST['zip_code'];
    $country = $_POST['country'];
    $department = $_POST['department'];
    $qualification = $_POST['qualification'];
    $aadhaar_number = $_POST['aadhaar_number'];
    $pan_number = $_POST['pan_number'];
    $username = $_POST['username'];
    $designation = $_POST['designation'];

    // Use prepared statements to prevent SQL injection
    $query = "UPDATE staff SET 
              first_name=?, middle_name=?, last_name=?, 
              date_of_birth=?, gender=?, blood_group=?, 
              caste=?, religion=?, phone_number=?, 
              alternate_phone_number=?, email=?, 
              alternate_email=?, address=?, city=?, 
              state=?, zip_code=?, country=?, 
              department=?, qualification=?, 
              aadhaar_number=?, pan_number=?, 
              username=?, designation=? 
              WHERE staff_id=?";

    $stmt = $mysqli->prepare($query);
    if (!$stmt) {
        die("SQL Error: " . $mysqli->error);
    }

    // Bind parameters
    $stmt->bind_param(
        "ssssssssssssssssssssssss",
        $first_name, $middle_name, $last_name,
        $date_of_birth, $gender, $blood_group,
        $caste, $religion, $phone_number,
        $alternate_phone_number, $email,
        $alternate_email, $address, $city,
        $state, $zip_code, $country,
        $department, $qualification,
        $aadhaar_number, $pan_number,
        $username, $designation, $staff_id
    );
   
    if ($stmt->execute()) {
        echo "
        <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    title: 'Success!',
                    text: 'Staff details updated successfully!',
                    icon: 'success',
                    confirmButtonText: 'OK'
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = 'staff_entry.php';
                    }
                });
            });
        </script>";
    } else {
        echo "
        <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    title: 'Error!',
                    text: 'Failed to update staff details.',
                    icon: 'error',
                    confirmButtonText: 'OK'
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = 'staff_entry.php';
                    }
                });
            });
        </script>";
    }
    $stmt->close();
    
}

// Fetch all staff details
$query = "SELECT * FROM staff";
$result = $mysqli->query($query);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Staff Management</title>
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
        <h2 class="my-4">Staff Management</h2>
        
 <!-- Search Bar -->
 <div class="mb-3">
            <input type="text" id="searchStaff" class="form-control" placeholder="Search by Staff Name or ID">
        </div>

        <!-- Staff Table -->
        <div class="table-responsive">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Profile Image</th>
                        <th>Staff ID</th>
                        <th>First Name</th>
                        <th>Middle Name</th>
                        <th>Last Name</th>
                        <th>Date of Birth</th>
                        <th>Gender</th>
                        <th>Blood Group</th>
                        <th>Caste</th>
                        <th>Religion</th>
                        <th>Phone Number</th>
                        <th>Alternate Phone</th>
                        <th>Email</th>
                        <th>Alternate Email</th>
                        <th>Address</th>
                        <th>City</th>
                        <th>State</th>
                        <th>Zip Code</th>
                        <th>Country</th>
                        <th>Department</th>
                        <th>Qualification</th>
                        <th>Aadhaar Number</th>
                        <th>PAN Number</th>
                        <th>Username</th>
                        <th>Designation</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <form method="POST" action="">
                                <td><img src="<?= $row['profile_image'] ?>" width="50" height="50"></td>
                                <td><?= $row['staff_id'] ?></td>
                                <td><input type="text" name="first_name" value="<?= $row['first_name'] ?>" disabled></td>
                                <td><input type="text" name="middle_name" value="<?= $row['middle_name'] ?>" disabled></td>
                                <td><input type="text" name="last_name" value="<?= $row['last_name'] ?>" disabled></td>
                                <td><input type="date" name="date_of_birth" value="<?= $row['date_of_birth'] ?>" disabled></td>
                                <td><input type="text" name="gender" value="<?= $row['gender'] ?>" disabled></td>
                                <td><input type="text" name="blood_group" value="<?= $row['blood_group'] ?>" disabled></td>
                                <td><input type="text" name="caste" value="<?= $row['caste'] ?>" disabled></td>
                                <td><input type="text" name="religion" value="<?= $row['religion'] ?>" disabled></td>
                                <td><input type="text" name="phone_number" value="<?= $row['phone_number'] ?>" disabled></td>
                                <td><input type="text" name="alternate_phone_number" value="<?= $row['alternate_phone_number'] ?>" disabled></td>
                                <td><input type="email" name="email" value="<?= $row['email'] ?>" disabled></td>
                                <td><input type="email" name="alternate_email" value="<?= $row['alternate_email'] ?>" disabled></td>
                                <td><input type="text" name="address" value="<?= $row['address'] ?>" disabled></td>
                                <td><input type="text" name="city" value="<?= $row['city'] ?>" disabled></td>
                                <td><input type="text" name="state" value="<?= $row['state'] ?>" disabled></td>
                                <td><input type="text" name="zip_code" value="<?= $row['zip_code'] ?>" disabled></td>
                                <td><input type="text" name="country" value="<?= $row['country'] ?>" disabled></td>
                                <td><input type="text" name="department" value="<?= $row['department'] ?>" disabled></td>
                                <td><input type="text" name="qualification" value="<?= $row['qualification'] ?>" disabled></td>
                                <td><input type="text" name="aadhaar_number" value="<?= $row['aadhaar_number'] ?>" disabled></td>
                                <td><input type="text" name="pan_number" value="<?= $row['pan_number'] ?>" disabled></td>
                                <td><input type="text" name="username" value="<?= $row['username'] ?>" disabled></td>
                                <td><input type="text" name="designation" value="<?= $row['designation'] ?>" disabled></td>
                                <td class="action-buttons">
                                    <input type="hidden" name="staff_id" value="<?= $row['staff_id'] ?>">
                                    <button type="button" class="btn btn-warning btn-sm edit-btn">Edit</button>
                                    <button type="submit" name="update" class="btn btn-success btn-sm update-btn" style="display: none;">Update</button>
                                    <button type="button" class="btn btn-danger btn-sm delete-btn" data-id="<?= $row['staff_id'] ?>">Delete</button>

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
document.getElementById('searchStaff').addEventListener('input', function() {
    const searchValue = this.value.toLowerCase().trim();
    const rows = document.querySelectorAll('tbody tr');

    rows.forEach(row => {
        const staffId = row.querySelector('td:nth-child(2)')?.textContent.toLowerCase().trim() || '';
        const firstName = row.querySelector('td:nth-child(3) input')?.value.toLowerCase().trim() || '';
        const middleName = row.querySelector('td:nth-child(4) input')?.value.toLowerCase().trim() || '';
        const lastName = row.querySelector('td:nth-child(5) input')?.value.toLowerCase().trim() || '';

        const fullName = `${firstName} ${middleName} ${lastName}`.trim();

        if (staffId.includes(searchValue) || fullName.includes(searchValue)) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    });
});

       // Delete Staff
       document.querySelectorAll('.delete-btn').forEach(button => {
            button.addEventListener('click', function() {
                const staffId = this.getAttribute('data-id');
                Swal.fire({
                    title: 'Are you sure?',
                    text: 'You will not be able to recover this staff record!',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Yes, delete it!',
                    cancelButtonText: 'No, cancel!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = `delete_staff.php?id=${staffId}`;
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