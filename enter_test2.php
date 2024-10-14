<?php
// enter_test_details.php

// Include your database connection
include('db.php'); 

// Start the session
session_start();

// Fetch and sanitize GET parameters using the null coalescing operator
$year = $_GET['year'] ?? '';
$semester = $_GET['semester'] ?? '';
$department = $_GET['department'] ?? '';
$test_type = $_GET['test_type'] ?? '';
$subject_name = $_GET['subject_name'] ?? '';
$subject_code = $_GET['subject_code'] ?? '';

// Initialize an empty array for students
$students = [];

// Validate and fetch student details based on test_type
if ($test_type) {
    // Define allowed test types to prevent SQL injection
    $allowed_test_types = ['serial_test1', 'serial_test2'];

    if (in_array($test_type, $allowed_test_types)) {
        // Since table names cannot be parameterized, ensure $test_type is safe
        // Use backticks to encapsulate table names to prevent SQL errors
        $safe_test_type = "`" . $test_type . "`";
        
        // Correct the column name if it's 'year' instead of 'years'
        // Adjust 'stud.year' based on your actual database schema
        $sql = "SELECT stud.register_no, stud.student_name, test.total_marks, ? AS test_type
                FROM stud
                LEFT JOIN $safe_test_type AS test ON stud.register_no = test.registration_no 
                WHERE stud.years = ? AND stud.department = ?"; // Ensure 'year' is the correct column name

        // Prepare the SQL statement
        if ($stmt = $mysqli->prepare($sql)) {
            // Bind parameters (test_type as a string, year as a string, department as a string)
            $stmt->bind_param("sss", $test_type, $year, $department);

            // Execute the statement
            $stmt->execute();

            // Get the result
            $result = $stmt->get_result();

            // Fetch all students
            while ($row = $result->fetch_assoc()) {
                $students[] = $row;
            }

            // Close the statement
            $stmt->close();
        } else {
            // Handle prepare() failure
            $_SESSION['failed'] = "Database query failed: " . $mysqli->error;
        }
    } else {
        // Invalid test_type provided
        $_SESSION['failed'] = "Invalid test type selected.";
    }
}

// Handle error or success messages
// These will be displayed in the HTML below
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Meta Tags -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Enter Test Details</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <!-- jQuery and Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <style>
        /* Custom Styles */
        .table-container {
            max-height: 500px; /* Set the desired height */
            overflow-y: auto;  /* Enable vertical scrolling */
            border: 1px solid #dee2e6; /* Optional: Add border to match Bootstrap table */
        }

        thead th {
            position: sticky;
            top: 0;
            background-color: #343a40; /* Match the .thead-dark background */
            color: white;
            z-index: 2;
        }

        /* Optional: Enhance table appearance */
        table {
            width: 100%;
        }

        .table-responsive {
            max-height: 300px; /* Adjust the height as needed */
        }

        h2 {
            text-align: center; /* Center align the main title */
            margin: 20px 0; /* Add vertical margin */
        }

        h4 {
            text-align: center; /* Center align the subsection title */
            margin-bottom: 30px; /* Add bottom margin */
        }

        .form-section {
            margin-bottom: 20px; /* Add spacing between form sections */
        }

        .btn {
            margin-top: 10px; /* Add spacing above buttons */
        }

        /* Adjustments for responsive design */
        .col-md-4, .col-md-8 {
            display: flex;
            flex-direction: column;
        }
    </style>
</head>
<body>
<div class="container-fluid">
    <h2>ENTER SERIAL TEST MARK</h2>
    <div class="row">
        <!-- Left Side: Scrollable Table -->
        <div class="col-md-4">
            <h4>Student Details</h4>
            <div class="form-group">
                <input type="text" id="searchBox" class="form-control" placeholder="Search by Register No or Student Name">
            </div>
            <div class="table-responsive">
                <table class="table table-bordered table-striped" id="studentTable">
                    <thead class="table-dark">
                        <tr>
                            <th>Register No</th>
                            <th>Student Name</th>
                            <th>Total Marks</th>
                            <th>Test Type</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($students)): ?>
                            <?php foreach ($students as $student): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($student['register_no']); ?></td>
                                    <td><?php echo htmlspecialchars($student['student_name']); ?></td>
                                    <td><?php echo htmlspecialchars($student['total_marks'] ?? 'N/A'); ?></td>
                                    <td><?php echo htmlspecialchars($student['test_type']); ?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="4" class="text-center">No students found.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Right Side: Form -->
        <div class="col-md-8">
            <!-- Display Error or Success Messages -->
            <?php if (isset($_SESSION['failed'])): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <?php echo htmlspecialchars($_SESSION['failed']); ?>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <?php unset($_SESSION['failed']); ?>
            <?php endif; ?>

            <?php if (isset($_SESSION['message'])): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <?php echo htmlspecialchars($_SESSION['message']); ?>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <?php unset($_SESSION['message']); ?>
            <?php endif; ?>

            <form method="POST" action="submit_test2.php" onsubmit="return validateTotalMarks()">
                <!-- Readonly Details in a Small Table Format -->
                <div class="form-section">
                    <h5>Test Information</h5>
                    <table class="table table-bordered readonly-table">
                        <tr>
                            <td><strong>Year:</strong></td>
                            <td><?php echo htmlspecialchars($year); ?></td>
                            <td><strong>Semester:</strong></td>
                            <td><?php echo htmlspecialchars($semester); ?></td>
                        </tr>
                        <tr>
                            <td><strong>Department:</strong></td>
                            <td><?php echo htmlspecialchars($department); ?></td>
                            <td><strong>Test Type:</strong></td>
                            <td><?php echo htmlspecialchars($test_type); ?></td>
                        </tr>
                        <tr>
                            <td><strong>Subject Name:</strong></td>
                            <td><?php echo htmlspecialchars($subject_name); ?></td>
                            <td><strong>Subject Code:</strong></td>
                            <td><?php echo htmlspecialchars($subject_code); ?></td>
                        </tr>
                    </table>
                </div>

                <!-- Hidden Fields -->
                <input type="hidden" name="year" value="<?php echo htmlspecialchars($year); ?>">
                <input type="hidden" name="semester" value="<?php echo htmlspecialchars($semester); ?>">
                <input type="hidden" name="department" value="<?php echo htmlspecialchars($department); ?>">
                <input type="hidden" name="test_type" value="<?php echo htmlspecialchars($test_type); ?>">
                <input type="hidden" name="subject_name" value="<?php echo htmlspecialchars($subject_name); ?>">
                <input type="hidden" name="subject_code" value="<?php echo htmlspecialchars($subject_code); ?>">

               <!-- Input Fields in Table Format -->
               <table class="table table-bordered">
                   <tbody>
                       <tr>
                           <td><label for="register_no"><strong>Register Number:</strong></label></td>
                           <td>
                               <input type="text" class="form-control" id="register_no" name="register_no" readonly>
                           </td>
                       </tr>
                       <tr>
                           <td><label for="student_name"><strong>Student Name:</strong></label></td>
                           <td>
                               <input type="text" class="form-control" id="student_name" name="student_name" readonly>
                           </td>
                       </tr>
                       <tr>
                           <td><label for="student_department"><strong>Student Department:</strong></label></td>
                           <td>
                               <input type="text" class="form-control" id="student_department" name="student_department" readonly>
                           </td>
                       </tr>
                   </tbody>
               </table>

               <!-- Marks Entry -->
               <div class="row">
                   <div class="col-md-6">
                       <h5>Part A</h5>
                       <table class="table table-bordered">
                           <?php for ($i = 1; $i <= 10; $i++): ?>
                               <tr>
                                   <td>
                                       <div class="form-group">
                                           <label for="part_a_<?php echo $i; ?>">Question <?php echo $i; ?>:</label>
                                           <input type="number" class="form-control part-a" min="0" max="2" id="part_a_<?php echo $i; ?>" name="part_a_<?php echo $i; ?>" oninput="calculateTotal()" required>
                                       </div>
                                   </td>
                               </tr>
                           <?php endfor; ?>
                       </table>
                   </div>

                   <div class="col-md-6">
                       <h5>Part B</h5>
                       <table class="table table-bordered">
                           <?php for ($i = 11; $i <= 15; $i++): ?>
                               <tr>
                                   <td>
                                       <div class="form-group">
                                           <label for="part_b_<?php echo $i; ?>a">Question <?php echo $i; ?>a:</label>
                                           <input type="number" class="form-control part-b" min="0" max="13" id="part_b_<?php echo $i; ?>a" name="part_b_<?php echo $i; ?>a" oninput="togglePartB('part_b_<?php echo $i; ?>a', 'part_b_<?php echo $i; ?>b')" required>
                                       </div>
                                   </td>
                               </tr>
                               <tr>
                                   <td>
                                       <div class="form-group">
                                           <label for="part_b_<?php echo $i; ?>b">Question <?php echo $i; ?>b:</label>
                                           <input type="number" class="form-control part-b" min="0" max="13" id="part_b_<?php echo $i; ?>b" name="part_b_<?php echo $i; ?>b" oninput="togglePartB('part_b_<?php echo $i; ?>b', 'part_b_<?php echo $i; ?>a')">
                                       </div>
                                   </td>
                               </tr>
                           <?php endfor; ?>
                           <tr>
                <td>
                    <div class="form-group">
                        <label for="part_b_16a">Question 16a:</label>
                        <input type="number" class="form-control part-b" min="0" max="15"  oninput="togglePartB('part_b_16a', 'part_b_16b')" id="part_b_16a" name="part_b_16a"  required>
                    </div>
                </td>
            </tr>
            <tr>
                <td>
                    <div class="form-group">
                        <label for="part_b_16b">Question 16b:</label>
                        <input type="number" class="form-control part-b" min="0" max="15" id="part_b_16b" name="part_b_16b" oninput="togglePartB('part_b_16a', 'part_b_16b')">
                    </div>
                </td>
            </tr>
                       </table>
                   </div>
               </div>

               <!-- Total Marks and Submit Button -->
               <div class="form-group">
                   <label for="total_marks">Total Marks:</label>
                   <input type="text" class="form-control" id="total_marks" name="total_marks" readonly>
               </div>

               <div class="d-flex justify-content-between">
                   <button type="button" class="btn btn-primary" id="editBtn">Edit</button>
                   <button type="submit" class="btn btn-success">Save</button>
                   <button type="button" class="btn btn-primary" id="submitBtn">Submit</button>
               </div>
            </form>
        </div>
    </div>
</div>


<!-- Additional jQuery for AJAX and Form Functionality -->
<script>
    document.getElementById('submitBtn').addEventListener('click', function() {
    window.location.href = 'staff_dashboard.php'; // Replace with your home page URL
});

    $(document).ready(function() {
    $('#searchBox').on('keyup', function() {
        var value = $(this).val().toLowerCase();
        
        // Apply search only to student table
        $('#studentTable tbody tr').filter(function() {
            $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
        });
    });
});
    // Fetch student details based on click event
$('#studentTable tbody').on('click', 'tr', function() {
    var registerNo = $(this).find('td:first').text().trim(); // Get the register number from the clicked row
    if (registerNo) {
        $.ajax({
            url: 'fetch_stud2.php',
            type: 'POST',
            data: { register_no: registerNo, test_type: '<?php echo $test_type; ?>' }, // Include test type from previous page
            success: function(data) {
                try {
                    var student = JSON.parse(data);
                    if (student && student.student_name) {

                        $('#register_no').val(registerNo);
                        $('#student_name').val(student.student_name);
                        $('#student_department').val(student.department);
                        // Populate marks into the corresponding input fields
                        $('#part_a_1').val(student.q1_marks || 0);
                        $('#part_a_2').val(student.q2_marks || 0);
                        $('#part_a_3').val(student.q3_marks || 0);
                        $('#part_a_4').val(student.q4_marks || 0);
                        $('#part_a_5').val(student.q5_marks || 0);
                        $('#part_a_6').val(student.q6_marks || 0);
                        $('#part_a_7').val(student.q7_marks || 0);
                        $('#part_a_8').val(student.q8_marks || 0);
                        $('#part_a_9').val(student.q9_marks || 0);
                        $('#part_a_10').val(student.q10_marks || 0);
                        $('#part_b_11a').val(student.q11A_marks || 0);
                        $('#part_b_11b').val(student.q11B_marks || 0);
                        $('#part_b_12a').val(student.q12A_marks || 0);
                        $('#part_b_12b').val(student.q12B_marks || 0);
                        $('#part_b_13a').val(student.q13A_marks || 0);
                        $('#part_b_13b').val(student.q13B_marks || 0);
                        $('#part_b_14a').val(student.q14A_marks || 0);
                        $('#part_b_14b').val(student.q14B_marks || 0);
                        $('#part_b_15a').val(student.q15A_marks || 0);
                        $('#part_b_15b').val(student.q15B_marks || 0);
                        $('#part_b_16a').val(student.q16A_marks || 0);
                        $('#part_b_16b').val(student.q16B_marks || 0);
                        calculateTotal();
                        disableInputFields(); // Recalculate total marks based on the populated fields
                    } else {
                        alert('Student not found');
                        clearStudentFields();
                    }
                } catch (e) {
                    alert('Error fetching student data');
                    clearStudentFields();
                }
            },
            error: function() {
                alert('Error connecting to server');
                clearStudentFields();
            }
        });
    } else {
        clearStudentFields();
    }
});

// Clear input fields if no student found
function clearStudentFields() {
    $('#register_no').val(''); 
    $('#student_name').val('');
    $('#student_department').val('');
    $('#part_a_1').val('');
    $('#part_a_2').val('');
    $('#part_a_3').val('');
    $('#part_a_4').val('');
    $('#part_a_5').val('');
    $('#part_a_6').val('');
    $('#part_a_7').val('');
    $('#part_a_8').val('');
    $('#part_a_9').val('');
    $('#part_a_10').val('');
    $('#part_b_11a').val('');
    $('#part_b_11b').val('');
    $('#part_b_12a').val('');
    $('#part_b_12b').val('');
    $('#part_b_13a').val('');
    $('#part_b_13b').val('');
    $('#part_b_14a').val('');
    $('#part_b_14b').val('');
    $('#part_b_15a').val('');
    $('#part_b_15b').val('');
    $('#part_b_16a').val('');
    $('#part_b_16b').val('');
}
// Function to disable input fields
function disableInputFields() {
    $('#register_no').prop('disabled', true);
    $('#student_name').prop('disabled', true);
    $('#student_department').prop('disabled', true);
    $('#part_a_1').prop('disabled', true);
    $('#part_a_2').prop('disabled', true);
    $('#part_a_3').prop('disabled', true);
    $('#part_a_4').prop('disabled', true);
    $('#part_a_5').prop('disabled', true);
    $('#part_a_6').prop('disabled', true);
    $('#part_a_7').prop('disabled', true);
    $('#part_a_8').prop('disabled', true);
    $('#part_a_9').prop('disabled', true);
    $('#part_a_10').prop('disabled', true);
    $('#part_b_11a').prop('disabled', true);
    $('#part_b_11b').prop('disabled', true);
    $('#part_b_12a').prop('disabled', true);
    $('#part_b_12b').prop('disabled', true);
    $('#part_b_13a').prop('disabled', true);
    $('#part_b_13b').prop('disabled', true);
    $('#part_b_14a').prop('disabled', true);
    $('#part_b_14b').prop('disabled', true);
    $('#part_b_15a').prop('disabled', true);
    $('#part_b_15b').prop('disabled', true);
    $('#part_b_16a').prop('disabled', true);
    $('#part_b_16b').prop('disabled', true);
}
$(document).ready(function() {
    // Handler for the Edit button
    $('#editBtn').on('click', function() {
        // Enable the input fields
        enableInputFields();
    });

    function enableInputFields() {
        $('#register_no').prop('disabled', false);
        $('#student_name').prop('disabled', false);
        $('#student_department').prop('disabled', false);
        $('#part_a_1').prop('disabled', false);
        $('#part_a_2').prop('disabled', false);
        $('#part_a_3').prop('disabled', false);
        $('#part_a_4').prop('disabled', false);
        $('#part_a_5').prop('disabled', false);
        $('#part_a_6').prop('disabled', false);
        $('#part_a_7').prop('disabled', false);
        $('#part_a_8').prop('disabled', false);
        $('#part_a_9').prop('disabled', false);
        $('#part_a_10').prop('disabled', false);
        $('#part_b_11a').prop('disabled', false);
        $('#part_b_11b').prop('disabled', false);
        $('#part_b_12a').prop('disabled', false);
        $('#part_b_12b').prop('disabled', false);
        $('#part_b_13a').prop('disabled', false);
        $('#part_b_13b').prop('disabled', false);
        $('#part_b_14a').prop('disabled', false);
        $('#part_b_14b').prop('disabled', false);
        $('#part_b_15a').prop('disabled', false);
        $('#part_b_15b').prop('disabled', false);
        $('#part_b_16a').prop('disabled', false);
        $('#part_b_16b').prop('disabled', false);
    }
});
    // Calculate total marks dynamically
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

    // Validate total marks before submission
    function validateTotalMarks() {
        const totalMarks = parseFloat($('#total_marks').val());

        if (totalMarks > 100) {
            alert('Total marks cannot exceed 100. Please adjust the scores.');
            return false;
        }

        return true;
    }

    // Toggle Part B fields to ensure only one is filled at a time
    function togglePartB(enteredField, counterpartField) {
        const enteredVal = $('#' + enteredField).val();

        if (enteredVal) {
            $('#' + counterpartField).attr('disabled', true).val('');
        } else {
            $('#' + counterpartField).attr('disabled', false);
        }

        calculateTotal();
    }
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.querySelector('form'); // Select your form
        const inputs = form.querySelectorAll('input, select, textarea'); // Select all focusable inputs
        const inputsArray = Array.prototype.slice.call(inputs); // Convert NodeList to Array

        inputsArray.forEach((input, index) => {
            input.addEventListener('keydown', function(e) {
                if (e.key === 'Enter') {
                    e.preventDefault(); // Prevent the default form submission

                    const nextIndex = index + 1;
                    if (nextIndex < inputsArray.length) {
                        inputsArray[nextIndex].focus(); // Move to the next input
                    } else {
                        form.submit(); // Submit the form if it's the last input
                    }
                }
            });
        });
    });
</script>

</body>
</html>
