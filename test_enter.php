<?php
include('db.php');

session_start();

// Retrieve questionCount from the session
$questionCount = $_GET['questionCount'] ?? 0;

// Ensure questionCount is not reset after saving marks
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // After saving marks, retain the questionCount in the session
    $_SESSION['questionCount'] = $questionCount;
}

if (!isset($_SESSION['year'], $_SESSION['semester'], $_SESSION['department'],$_SESSION['section'], $_SESSION['test_type'], $_SESSION['subject_name'], $_SESSION['subject_code'])) {
    die("Session variables not set. Please configure the test first.");
}

// Fetch and sanitize session variables
$year = $_SESSION['year'] ?? '';
$semester = $_SESSION['semester'] ?? '';
$department = $_SESSION['department'] ?? '';
$section = $_SESSION['section'] ?? '';
$test_type = $_SESSION['test_type'] ?? '';
$subject_name = $_SESSION['subject_name'] ?? '';
$subject_code = $_SESSION['subject_code'] ?? '';

// Retrieve question count from URL
$questionCount = isset($_GET['questionCount']) ? (int)$_GET['questionCount'] : 0;

// Fetch students from the stud table based on year and department
$students = [];
if (!empty($year) && !empty($department)) {
    $query = "SELECT student_id, register_no, student_name, section FROM stud WHERE years = ? AND department = ? AND section = ?";    $stmt = $mysqli->prepare($query);
    if ($stmt) {
        $stmt->bind_param("iss", $year, $department,$section);
        $stmt->execute();
        $result = $stmt->get_result();
        while ($row = $result->fetch_assoc()) {
            $students[] = $row;
        }
        $stmt->close();
    }
}

// Display success or error messages

if (isset($_SESSION['success'])) {
    echo "
    <div class='alert alert-success alert-dismissible fade show' role='alert' style='position: fixed; top: 10px; left: 50%; transform: translateX(-50%); z-index: 1050;'>
        " . $_SESSION['success'] . "
        <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
    </div>
    <script>
        setTimeout(() => {
            document.querySelector('.alert-success').remove();
        }, 3000);
    </script>
    ";
    unset($_SESSION['success']);
}

if (isset($_SESSION['failed'])) {
    echo "
    <div class='alert alert-danger alert-dismissible fade show' role='alert' style='position: fixed; top: 10px; left: 50%; transform: translateX(-50%); z-index: 1050;'>
        " . $_SESSION['failed'] . "
        <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
    </div>
    <script>
        setTimeout(() => {
            document.querySelector('.alert-danger').remove();
        }, 3000);
    </script>
    ";
    unset($_SESSION['failed']);
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Test Marks Entry</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .table {
            width: 100%;
            /* Ensure tables use full width */
            margin-bottom: 1rem;
            /* Spacing below tables */
        }

        .table-bordered {
            border: 1px solid #dee2e6;
            /* Border around tables */
        }

        .table-bordered td,
        .table-bordered th {
            border: 1px solid #dee2e6;
            /* Borders for table cells */
        }

        .table th {
            background-color: #343a40;
            /* Dark background for table headers */
            color: white;
            /* White text for headers */
        }

        /* Form Section Styles */
        .form-section {
            background-color: #ffffff;
            /* White background for form section */
            padding: 20px;
            /* Padding around form */
            border-radius: 5px;
            /* Slightly rounded corners */
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            /* Subtle shadow for depth */
        }

        /* Input Styles */
        .form-control {
            border: 1px solid #ced4da;
            /* Standard border for inputs */
            border-radius: 5px;
            /* Rounded corners for inputs */
            padding: 5px;
            /* Reduced padding within inputs */
            transition: border-color 0.3s;
            /* Smooth transition for border color */
            font-size: 0.9rem;
            /* Smaller font size */
        }

        .form-control:focus {
            border-color: #007bff;
            /* Change border color on focus */
            box-shadow: 0 0 5px rgba(0, 123, 255, 0.5);
            /* Add shadow on focus */
        }

        /* Button Styles */
        .btn {
            padding: 10px 15px;
            /* Padding for buttons */
            border-radius: 5px;
            /* Rounded corners for buttons */
            transition: background-color 0.3s;
            /* Smooth background transition */
        }

        .btn-info {
            background-color: #17a2b8;
            /* Info button color */
            color: white;
            /* Text color for buttons */
        }

        .btn-info:hover {
            background-color: #138496;
            /* Darker shade on hover */
        }

        .btn-success {
            background-color: #28a745;
            /* Success button color */
        }

        .btn-success:hover {
            background-color: #218838;
            /* Darker shade on hover */
        }

        .btn-primary {
            background-color: #007bff;
            /* Primary button color */
        }

        .btn-primary:hover {
            background-color: #0056b3;
            /* Darker shade on hover */
        }

        /* Label Styles */
        label {
            font-weight: bold;
            /* Bold labels for clarity */
        }

        /* Total Marks Section */
        .mb-3 {
            margin-bottom: 1rem;
            /* Spacing below total mark input */
        }

        #total_mark {
            font-weight: bold;
            /* Bold total mark display */
        }

        /* Responsive Design */
        @media (max-width: 768px) {

            /* Adjustments for smaller screens */
            .form-section {
                padding: 15px;
                /* Less padding on smaller screens */
            }

            .table th,
            .table td {
                font-size: 0.9rem;
                /* Slightly smaller font size */
            }

            .btn {
                width: 100%;
                /* Full-width buttons on smaller screens */
                margin-bottom: 10px;
                /* Spacing between buttons */
            }
        }

        /* Small Text Styles */
        .small-text,
        #attendance {
            font-size: medium;
            /* Medium text for details */
        }

        /* Adjusted Input Box Size */
        #register_no,
        #student_name {
            width: 400px;
            /* Reduced width for inputs */
        }

        h4 {
            text-align: justify;
            margin-left: 160px;
        }

        /* Header Style */
        h2 {
            text-align: center;
            margin-top: 50px;
            /* Center align the header */
            margin-bottom: 50px;
            margin-left: 500px;
        }

        .col-md-4 {
            margin-top: 20px;
            /* Adjust the top margin to move it down */
            margin-bottom: 30px;
            /* Adjust the bottom margin to move it up */
            padding: 20px;
            /* Optional: add padding inside the column */
            border: 1px solid #dee2e6;
            /* Optional: Add a border for visual separation */
            border-radius: 5px;
            /* Optional: Rounded corners for aesthetics */
        }

        /* Responsive adjustments if needed */
        @media (max-width: 768px) {
            .col-md-4 {
                margin-top: 10px;
                /* Smaller top margin for mobile */
                margin-bottom: 20px;
                /* Smaller bottom margin for mobile */
            }
        }
    </style>
</head>

<body>
    <div class="container-fluid">
        <h2>ENTER SERIAL TEST MARK</h2>
        <div class="row">
            <!-- Left Side: Scrollable Table -->
            <div class="col-md-4">
                <h4>Student Lists</h4>
                <div class="form-group">
                    <input type="text" id="searchBox" class="form-control" placeholder="Search by Register No or Student Name">
                </div>
                <div class="table-responsive" style="max-height: 300px; overflow-y: scroll;">
                    <table class="table table-bordered table-striped" id="studentTable">
                        <thead class="table-dark">
                            <tr>
                                <th>Register No</th>
                                <th>Student Name</th>
                                <th>Section</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($students)): ?>
                                <?php foreach ($students as $student): ?>
                                    <tr class="student-row" data-register-no="<?php echo htmlspecialchars($student['register_no']); ?>" 
    data-student-name="<?php echo htmlspecialchars($student['student_name']); ?>"
    data-section="<?php echo htmlspecialchars($student['section'] ?? 'N/A'); ?>">
    <td><?php echo htmlspecialchars($student['register_no']); ?></td>
    <td><?php echo htmlspecialchars($student['student_name']); ?></td>
    <td><?php echo htmlspecialchars($student['section'] ?? 'Not Available'); ?></td>
</tr>

                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="2" class="text-center">No students found.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Right Side: Marks Entry Form -->
            <div class="col-md-8">
                <table class="table table-sm table-bordered readonly-table">
                    <tbody>
                        <tr>
                            <td><strong>Year:</strong> <?php echo htmlspecialchars($year); ?></td>
                            <td><strong>Semester:</strong> <?php echo htmlspecialchars($semester); ?></td>
                        </tr>
                        <tr>
                            <td><strong>Department:</strong> <?php echo htmlspecialchars($department); ?></td>
                            <td><strong>Test Type:</strong> <?php echo htmlspecialchars($test_type); ?></td>
                        </tr>
                        <tr>
                            <td><strong>Subject Name:</strong> <?php echo htmlspecialchars($subject_name); ?></td>
                            <td><strong>Subject Code:</strong> <?php echo htmlspecialchars($subject_code); ?></td>
                        </tr>
                    </tbody>
                </table>
                <div class="form-section">
                    <form id="marksForm" action="save_marks.php" method="POST">
                        <table class="table table-bordered">
                            <tbody>
                                <tr>
                                    <td class="small-text"><label for="register_no"><strong>Register Number:</strong></label></td>
                                    <td>
                                        <input type="text" class="form-control" id="register_no" name="register_no" readonly required>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="small-text"><label for="student_name"><strong>Student Name:</strong></label></td>
                                    <td>
                                        <input type="text" class="form-control" id="student_name" name="student_name" readonly>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="attendance-checkbox" colspan="2">
                                        <input type="checkbox" name="attendance" value="1" checked>
                                        <label for="attendance">Attendance</label>
                                    </td>
                                </tr>
                            </tbody>
                        </table>

                        <input type="hidden" name="year" value="<?php echo htmlspecialchars($year); ?>">
                        <input type="hidden" name="semester" value="<?php echo htmlspecialchars($semester); ?>">
                        <input type="hidden" name="department" value="<?php echo htmlspecialchars($department); ?>">
                        <input type="hidden" name="section" value="<?php echo htmlspecialchars($section); ?>">
                        <input type="hidden" name="test_type" value="<?php echo htmlspecialchars($test_type); ?>">
                        <input type="hidden" name="subject_name" value="<?php echo htmlspecialchars($subject_name); ?>">
                        <input type="hidden" name="subject_code" value="<?php echo htmlspecialchars($subject_code); ?>">
                        <input type="hidden" name="questionCount" value="<?php echo htmlspecialchars($questionCount); ?>">
                        <table class="table table-bordered" id="marksTable">
                            <thead>
                                <tr>
                                    <th>Question No</th>
                                    <th>Marks</th>
                                    <th>Attended</th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php for ($i = 1; $i <= $questionCount; $i++): ?>
    <tr>
        <td><?php echo $i; ?></td>
        <td><input type="number" class="form-control marks-input" name="marks[<?= $i ?>]" required></td>
        <td>
            <input type="hidden" name="attended[<?= $i ?>]" value="0"> <!-- Ensures unchecked checkboxes send '0' -->
            <input type="checkbox" name="attended[<?= $i ?>]" value="1" checked> Attended
        </td>
    </tr>
<?php endfor; ?>

                            </tbody>
                        </table>
                        <div class="mb-3">
                            <label for="total_mark">Total Mark:</label>
                            <input type="number" id="total_mark" class="form-control" readonly>
                        </div>
                        <button type="submit" class="btn btn-success">Save</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        // Populate student details when a row is clicked
        $(document).on('click', '.student-row', function() {
            const registerNo = $(this).data('register-no');
            const studentName = $(this).data('student-name');
            const section = $(this).data('section'); 
            $('#register_no').val(registerNo);
            $('#student_name').val(studentName);
            $('#section').val(section);
        });

        // Calculate total marks
        $('.marks-input').on('input', function() {
            let total = 0;
            $('.marks-input').each(function() {
                total += parseFloat($(this).val()) || 0;
            });
            $('#total_mark').val(total);
        });

        // Search functionality
        $('#searchBox').on('keyup', function() {
            const value = $(this).val().toLowerCase();
            $('#studentTable tr').filter(function() {
                $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
            });
        });

        // Form validation
        $('#marksForm').on('submit', function(e) {
            if ($('#register_no').val() === '') {
                alert('Please select a student by clicking on a row in the student list.');
                e.preventDefault();
            }
        });
    </script>
</body>

</html>