<?php
// Include your database connection
include('db.php'); 

// Assuming these values are being passed from the previous page
$year = $_GET['year'];
$semester = $_GET['semester'];
$department = $_GET['department'];
$test_type = $_GET['test_type'];
$subject_name = $_GET['subject_name'];
$subject_code = $_GET['subject_code'];

session_start();

// Check if the message is set
if (isset($_SESSION['message'])) {
    // Display the message with a close button
    echo "
    <div class='alert alert-success alert-dismissible fade show' role='alert'>
        " . $_SESSION['message'] . "
        <button type='button' class='close' data-dismiss='alert' aria-label='Close'>
            <span aria-hidden='true'>&times;</span>
        </button>
    </div>";
    // Unset the message so it doesn't show again
    unset($_SESSION['message']);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Enter Test Details</title>
    <!-- Bootstrap CSS -->
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">

<!-- jQuery and Bootstrap JS -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <style>
    .container .row {
        display: flex;
    }
    .col-md-6 {
        display: flex;
        flex-direction: column;
    }
    .table {
        flex-grow: 1;
    }
    .table td {
        vertical-align: middle; /* Align the content vertically in the middle */
    }
</style>
</head>
<body>
<div class="container">
    <h2>Enter Test Details</h2>
    <form method="POST" action="submit_test.php" onsubmit="return validateTotalMarks()">
        <div class="form-group">
            <label for="year">Year:</label>
            <input type="text" class="form-control" id="year" name="year" value="<?php echo $year; ?>" readonly>
        </div>
        <div class="form-group">
            <label for="semester">Semester:</label>
            <input type="text" class="form-control" id="semester" name="semester" value="<?php echo $semester; ?>" readonly>
        </div>
        <div class="form-group">
            <label for="department">Department:</label>
            <input type="text" class="form-control" id="department" name="department" value="<?php echo $department; ?>" readonly>
        </div>
        <div class="form-group">
            <label for="test_type">Test Type:</label>
            <input type="text" class="form-control" id="test_type" name="test_type" value="<?php echo $test_type; ?>" readonly>
        </div>
        <div class="form-group">
            <label for="subject_name">Subject Name:</label>
            <input type="text" class="form-control" id="subject_name" name="subject_name" value="<?php echo $subject_name; ?>" readonly>
        </div>
        <div class="form-group">
            <label for="subject_code">Subject Code:</label>
            <input type="text" class="form-control" id="subject_code" name="subject_code" value="<?php echo $subject_code; ?>" readonly>
        </div>
        <div class="form-group">
            <label for="register_no">Register Number:</label>
            <input type="text" class="form-control" id="register_no" name="register_no" required>
        </div>
        <div class="form-group">
            <label for="student_name">Student Name:</label>
            <input type="text" class="form-control" id="student_name" name="student_name" readonly>
        </div>
        <div class="form-group">
            <label for="student_department">Student Department:</label>
            <input type="text" class="form-control" id="student_department" name="student_department" readonly>
        </div>
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <h4>Part A</h4>
                    <table class="table table-bordered">
                        <?php for ($i = 1; $i <= 10; $i++): ?>
                            <tr>
                                <td>
                                    <div class="form-group">
                                        <label for="part_a_<?php echo $i; ?>">Question <?php echo $i; ?>:</label>
                                        <input type="number" class="form-control part-a" min="0" max="2"  id="part_a_<?php echo $i; ?>" name="part_a_<?php echo $i; ?>" oninput="calculateTotal()" required>
                                    </div>
                                </td>
                            </tr>
                        <?php endfor; ?>
                    </table>
                </div>

              <div class="col-md-6">
    <h4>Part B</h4>
    <table class="table table-bordered">
        <?php for ($i = 11; $i <= 13; $i++): ?>
            <tr>
                <td>
                    <div class="form-group">
                        <label for="part_b_<?php echo $i; ?>a">Question <?php echo $i; ?>a:</label>
                        <input type="number" class="form-control part-b" min="0" max="13"  id="part_b_<?php echo $i; ?>a" name="part_b_<?php echo $i; ?>a" oninput="togglePartB('part_b_<?php echo $i; ?>a', 'part_b_<?php echo $i; ?>b')" required>
                    </div>
                </td>
            </tr>
            <tr>
                <td>
                    <div class="form-group">
                        <label for="part_b_<?php echo $i; ?>b">Question <?php echo $i; ?>b:</label>
                        <input type="number" class="form-control part-b" min="0" max="13"  id="part_b_<?php echo $i; ?>b" name="part_b_<?php echo $i; ?>b" oninput="togglePartB('part_b_<?php echo $i; ?>b', 'part_b_<?php echo $i; ?>a')">
                    </div>
                </td>
            </tr>
        <?php endfor; ?>
    </table>
</div>

                    </table>
                </div>
            </div>
            <div class="form-group">
                <label for="total_marks">Total Marks:</label>
                <input type="text" class="form-control" id="total_marks" name="total_marks" readonly>
            </div>
            <button type="submit" class="btn btn-primary">Submit</button>
        </div>
    </form>
</div>

<script>
    $('#register_no').on('change', function() {
        var registerNo = $(this).val();
        if (registerNo) {
            $.ajax({
                url: 'fetch_stud.php',
                type: 'POST',
                data: {register_no: registerNo},
                success: function(data) {
                    var student = JSON.parse(data);
                    if (student) {
                        $('#student_name').val(student.student_name);
                        $('#student_department').val(student.department);
                    } else {
                        alert('Student not found');
                    }
                }
            });
        } else {
            $('#student_name').val('');
            $('#student_department').val('');
        }
    });

    function calculateTotal() {
        var total = 0;
        $('.part-a').each(function() {
            total += parseFloat($(this).val()) || 0;
        });
        $('.part-b').each(function() {
            total += parseFloat($(this).val()) || 0;
        });
        $('#total_marks').val(total);
    }

    function validateTotalMarks() {
        const totalMarks = parseFloat($('#total_marks').val());

        if (totalMarks > 60) {
            alert('Total marks cannot exceed 60. Please adjust the scores.');
            return false;
        }

        return true;
    }

    function togglePartB(enteredField, counterpartField) {
        const enteredVal = $('#' + enteredField).val();
        
        if (enteredVal) {
            $('#' + counterpartField).attr('disabled', true);
        } else {
            $('#' + counterpartField).attr('disabled', false);
        }

        calculateTotal();
    }
</script>

</body>
</html>  