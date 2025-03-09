<?php
include("db.php");

// Handle Update Subject Form Submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_subject'])) {
    $subject_id = strtoupper($_POST['subject_id']);
    $subject_name = strtoupper($_POST['subject_name']);
    $semester = strtoupper($_POST['semester']);
    $credit_points = strtoupper($_POST['credit_points']);
    $department = strtoupper($_POST['department']);
    $subject_code = strtoupper($_POST['subject_code']);
    $type = strtoupper($_POST['type']);
    $sub_type = strtoupper($_POST['sub_type']);
    $years = strtoupper($_POST['years']);
    $total_hours = strtoupper($_POST['total_hours']);
    
    $sql = "UPDATE subjects SET subject_name=?, semester=?, credit_points=?, department=?, subject_code=?, type=?, sub_type=?, years=?, total_hours=? WHERE subject_id=?";
    $stmt = $mysqli->prepare($sql);
    $stmt->bind_param("ssdssssiii", $subject_name, $semester, $credit_points, $department, $subject_code, $type, $sub_type, $years, $total_hours, $subject_id);

    if ($stmt->execute()) {
        echo "<script>Swal.fire('Success!', 'Subject updated successfully.', 'success');</script>";
    } else {
        echo "<script>Swal.fire('Error!', 'Failed to update subject.', 'error');</script>";
    }
    $stmt->close();
}

// Fetch Subjects
$sql = "SELECT * FROM subjects";
$result = $mysqli->query($sql);
$subjects = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $subjects[] = $row;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Subjects Management</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
    <div class="container mt-5">
        <h2>Subjects Management</h2>
        <button class="btn btn-primary mb-3" onclick="window.location.href='subject_entry.php'">Add Subject</button>
        
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Subject ID</th>
                    <th>Subject Name</th>
                    <th>Semester</th>
                    <th>Credit Points</th>
                    <th>Department</th>
                    <th>Subject Code</th>
                    <th>Type</th>
                    <th>Sub Type</th>
                    <th>Years</th>
                    <th>Total Hours</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($subjects as $subject): ?>
                    <tr>
                        <td><?php echo $subject['subject_id']; ?></td>
                        <td>
                            <span class="display-mode"><?php echo $subject['subject_name']; ?></span>
                            <input type="text" class="form-control edit-mode" name="subject_name" value="<?php echo $subject['subject_name']; ?>" style="display:none;">
                        </td>
                        <td>
                            <span class="display-mode"><?php echo $subject['semester']; ?></span>
                            <select class="form-control edit-mode" name="semester" style="display:none;">
                                <?php for ($i = 1; $i <= 8; $i++): ?>
                                    <option value="<?php echo $i; ?>" <?php echo $subject['semester'] == $i ? 'selected' : ''; ?>><?php echo $i; ?></option>
                                <?php endfor; ?>
                            </select>
                        </td>
                        <td>
                            <span class="display-mode"><?php echo $subject['credit_points']; ?></span>
                            <input type="number" step="0.01" class="form-control edit-mode" name="credit_points" value="<?php echo $subject['credit_points']; ?>" style="display:none;">
                        </td>
                        <td>
                            <span class="display-mode"><?php echo $subject['department']; ?></span>
                            <input type="text" class="form-control edit-mode" name="department" value="<?php echo $subject['department']; ?>" style="display:none;">
                        </td>
                        <td>
                            <span class="display-mode"><?php echo $subject['subject_code']; ?></span>
                            <input type="text" class="form-control edit-mode" name="subject_code" value="<?php echo $subject['subject_code']; ?>" style="display:none;">
                        </td>
                        <td>
                            <span class="display-mode"><?php echo $subject['type']; ?></span>
                            <input type="text" class="form-control edit-mode" name="type" value="<?php echo $subject['type']; ?>" style="display:none;">
                        </td>
                        <td>
                            <span class="display-mode"><?php echo $subject['sub_type']; ?></span>
                            <input type="text" class="form-control edit-mode" name="sub_type" value="<?php echo $subject['sub_type']; ?>" style="display:none;">
                        </td>
                        <td>
                            <span class="display-mode"><?php echo $subject['years']; ?></span>
                            <input type="number" class="form-control edit-mode" name="years" value="<?php echo $subject['years']; ?>" style="display:none;">
                        </td>
                        <td>
                            <span class="display-mode"><?php echo $subject['total_hours']; ?></span>
                            <input type="number" class="form-control edit-mode" name="total_hours" value="<?php echo $subject['total_hours']; ?>" style="display:none;">
                        </td>
                        <td>
                            <button class="btn btn-primary btn-edit btn-sm">Edit</button>
                            <button class="btn btn-success btn-update" style="display:none;">Update</button>
                            <button class="btn btn-danger btn-delete" data-id="<?php echo $subject['subject_code']; ?>">Delete</button>

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
                    formData.append('update_subject', true);
                    formData.append('subject_id', row.querySelector('td').innerText);
                    formData.append('subject_name', row.querySelectorAll('.edit-mode')[0].value);
                    formData.append('semester', row.querySelectorAll('.edit-mode')[1].value);
                    formData.append('credit_points', row.querySelectorAll('.edit-mode')[2].value);
                    formData.append('department', row.querySelectorAll('.edit-mode')[3].value);
                    formData.append('subject_code', row.querySelectorAll('.edit-mode')[4].value);
                    formData.append('type', row.querySelectorAll('.edit-mode')[5].value);
                    formData.append('sub_type', row.querySelectorAll('.edit-mode')[6].value);
                    formData.append('years', row.querySelectorAll('.edit-mode')[7].value);
                    formData.append('total_hours', row.querySelectorAll('.edit-mode')[8].value);

                    fetch('subject_manage.php', {
                        method: 'POST',
                        body: formData
                    })
                    .then(response => response.text())
                    .then(result => {
                        if (result.includes('success')) {
                            Swal.fire('Updated!', 'Subject details have been updated.', 'success');
                            row.querySelectorAll('.edit-mode').forEach(input => {
                                input.style.display = 'none';
                                input.previousElementSibling.style.display = 'inline-block';
                                input.previousElementSibling.innerText = input.value;
                            });
                            this.style.display = 'none';
                            row.querySelector('.btn-edit').style.display = 'inline-block';
                        } else {
                            Swal.fire('Error!', 'Failed to update subject details.', 'error');
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
                            window.location.href = `delete_subject.php?id=${departmentId}`;
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