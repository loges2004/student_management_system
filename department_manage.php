<?php
include("db.php");
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Handle Update Department Form Submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_department'])) {
    // Debugging: Print received POST data
    echo "<pre>";
    print_r($_POST);
    echo "</pre>";

    $department_id = strtoupper($_POST['department_id']);
    $program_type = strtoupper($_POST['program_type']);
    $degree_type = strtoupper($_POST['degree_type']);
    $department_name = strtoupper($_POST['department_name']);
    $year = $_POST['year']; 

    // Debugging: Print sanitized data
    echo "Department ID: $department_id<br>";
    echo "Program Type: $program_type<br>";
    echo "Degree Type: $degree_type<br>";
    echo "Department Name: $department_name<br>";
    echo "Year: $year<br>";

    // Correct SQL query for updating departments
    $sql = "UPDATE departments SET program_type=?, degree_type=?, department_name=?, year=? WHERE department_id=?";
    $stmt = $mysqli->prepare($sql);

    if ($stmt === false) {
        die("Prepare failed: " . $mysqli->error);
    }

    // Correct bind_param for departments table
    $stmt->bind_param("sssss", $program_type, $degree_type, $department_name, $year, $department_id);

    if ($stmt->execute()) {
        echo "<script>Swal.fire('Success!', 'Department updated successfully.', 'success');</script>";
    } else {
        echo "<script>Swal.fire('Error!', 'Failed to update department: " . $stmt->error . "', 'error');</script>";
    }
    $stmt->close();
}

// Fetch Departments
$sql = "SELECT * FROM departments";
$result = $mysqli->query($sql);
$departments = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $departments[] = $row;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Departments</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
    <div class="container mt-5">
        <h2>Departments</h2>
        <button class="btn btn-primary mb-3" onclick="window.location.href='department_entry.php';">
            Add Department
        </button>

        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Department ID</th>
                    <th>Program Type</th>
                    <th>Degree Type</th>
                    <th>Department Name</th>
                    <th>Year</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($departments as $dept): ?>
                    <tr>
                        <td><?php echo $dept['department_id']; ?></td>
                        <td>
                            <span class="display-mode"><?php echo $dept['program_type']; ?></span>
                            <input type="text" class="form-control edit-mode" name="program_type" value="<?php echo $dept['program_type']; ?>" style="display:none;">
                        </td>
                        <td>
                            <span class="display-mode"><?php echo $dept['degree_type']; ?></span>
                            <input type="text" class="form-control edit-mode" name="degree_type" value="<?php echo $dept['degree_type']; ?>" style="display:none;">
                        </td>
                        <td>
                            <span class="display-mode"><?php echo $dept['department_name']; ?></span>
                            <input type="text" class="form-control edit-mode" name="department_name" value="<?php echo $dept['department_name']; ?>" style="display:none;">
                        </td>
                        <td>
                            <span class="display-mode"><?php echo $dept['year']; ?></span>
                            <input type="number" class="form-control edit-mode" name="year" value="<?php echo $dept['year']; ?>" style="display:none;">
                        </td>
                        <td>
                            <button class="btn btn-primary btn-edit">Edit</button>
                            <button class="btn btn-success btn-update" style="display:none;">Update</button>
                            <button class="btn btn-danger btn-delete" data-id="<?php echo $dept['department_id']; ?>">Delete</button>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.btn-edit').forEach(button => {
                button.addEventListener('click', function() {
                    const row = this.closest('tr');
                    row.querySelectorAll('.edit-mode').forEach(input => {
                        input.style.display = 'inline-block';
                        input.previousElementSibling.style.display = 'none';
                    });
                    this.style.display = 'none';
                    row.querySelector('.btn-update').style.display = 'inline-block';
                });
            });

            document.querySelectorAll('.btn-update').forEach(button => {
                button.addEventListener('click', function() {
                    const row = this.closest('tr');
                    const formData = new FormData();
                    formData.append('update_department', true);
                    formData.append('department_id', row.querySelector('td').innerText);
                    formData.append('program_type', row.querySelectorAll('.edit-mode')[0].value);
                    formData.append('degree_type', row.querySelectorAll('.edit-mode')[1].value);
                    formData.append('department_name', row.querySelectorAll('.edit-mode')[2].value);
                    formData.append('year', row.querySelectorAll('.edit-mode')[3].value);

                    fetch('department_manage.php', {
                        method: 'POST',
                        body: formData
                    })
                    .then(response => response.text())
                    .then(result => {
                        if (result.includes('success')) {
                            Swal.fire('Updated!', 'Department details have been updated.', 'success');
                            row.querySelectorAll('.edit-mode').forEach(input => {
                                input.style.display = 'none';
                                input.previousElementSibling.style.display = 'inline-block';
                                input.previousElementSibling.innerText = input.value;
                            });
                            this.style.display = 'none';
                            row.querySelector('.btn-edit').style.display = 'inline-block';
                        } else {
                            Swal.fire('Error!', 'Failed to update department details.', 'error');
                        }
                    });
                });
            });

            // Delete functionality
            document.querySelectorAll('.btn-delete').forEach(button => {
                button.addEventListener('click', function() {
                    const departmentId = this.getAttribute('data-id');
                    Swal.fire({
                        title: 'Are you sure?',
                        text: 'You will not be able to recover this department record!',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonText: 'Yes, delete it!',
                        cancelButtonText: 'No, cancel!'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.location.href = `delete_department.php?id=${departmentId}`;
                        }
                    });
                });
            });
        });
    </script>
</body>
</html>
<?php
$mysqli->close();
?>