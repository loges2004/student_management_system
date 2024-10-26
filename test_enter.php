<?php
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

// Retrieve question count from URL
$questionCount = isset($_GET['questionCount']) ? (int)$_GET['questionCount'] : 0;

$students = [];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Test Marks Entry</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        /* Basic Table Styles */
.table {
    width: 100%; /* Ensure tables use full width */
    margin-bottom: 1rem; /* Spacing below tables */
}

.table-bordered {
    border: 1px solid #dee2e6; /* Border around tables */
}

.table-bordered td,
.table-bordered th {
    border: 1px solid #dee2e6; /* Borders for table cells */
}

.table th {
    background-color: #343a40; /* Dark background for table headers */
    color: white; /* White text for headers */
}

/* Form Section Styles */
.form-section {
    background-color: #ffffff; /* White background for form section */
    padding: 20px; /* Padding around form */
    border-radius: 5px; /* Slightly rounded corners */
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1); /* Subtle shadow for depth */
}

/* Input Styles */
.form-control {
    border: 1px solid #ced4da; /* Standard border for inputs */
    border-radius: 5px; /* Rounded corners for inputs */
    padding: 5px; /* Reduced padding within inputs */
    transition: border-color 0.3s; /* Smooth transition for border color */
    font-size: 0.9rem; /* Smaller font size */
}

.form-control:focus {
    border-color: #007bff; /* Change border color on focus */
    box-shadow: 0 0 5px rgba(0, 123, 255, 0.5); /* Add shadow on focus */
}

/* Button Styles */
.btn {
    padding: 10px 15px; /* Padding for buttons */
    border-radius: 5px; /* Rounded corners for buttons */
    transition: background-color 0.3s; /* Smooth background transition */
}

.btn-info {
    background-color: #17a2b8; /* Info button color */
    color: white; /* Text color for buttons */
}

.btn-info:hover {
    background-color: #138496; /* Darker shade on hover */
}

.btn-success {
    background-color: #28a745; /* Success button color */
}

.btn-success:hover {
    background-color: #218838; /* Darker shade on hover */
}

.btn-primary {
    background-color: #007bff; /* Primary button color */
}

.btn-primary:hover {
    background-color: #0056b3; /* Darker shade on hover */
}

/* Label Styles */
label {
    font-weight: bold; /* Bold labels for clarity */
}

/* Total Marks Section */
.mb-3 {
    margin-bottom: 1rem; /* Spacing below total mark input */
}

#total_mark {
    font-weight: bold; /* Bold total mark display */
}

/* Responsive Design */
@media (max-width: 768px) {
    /* Adjustments for smaller screens */
    .form-section {
        padding: 15px; /* Less padding on smaller screens */
    }

    .table th,
    .table td {
        font-size: 0.9rem; /* Slightly smaller font size */
    }

    .btn {
        width: 100%; /* Full-width buttons on smaller screens */
        margin-bottom: 10px; /* Spacing between buttons */
    }
}

/* Small Text Styles */
.small-text, #attendance {
    font-size: medium; /* Medium text for details */
}

/* Adjusted Input Box Size */
#register_no, #student_name {
    width: 400px; /* Reduced width for inputs */
}
h4{
    text-align: justify;
    margin-left: 160px;
}
/* Header Style */
h2 {
    text-align: center; /* Center align the header */

}

    </style>
</head>
<body>
    
<div class="container-fluid">
<h2 class="my-4">ENTER SERIAL TEST MARK</h2>

    <div class="row">
        <!-- Left Side: Scrollable Table -->
        <div class="col-md-4">
            <h4>Student Lists</h4>
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
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($students)): ?>
                            <?php foreach ($students as $student): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($student['register_no']); ?></td>
                                    <td><?php echo htmlspecialchars($student['student_name']); ?></td>
                                    <td><?php echo htmlspecialchars($student['total_marks'] ?? 'N/A'); ?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="3" class="text-center">No students found.</td>
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
                <table class="table table-bordered">
                    <tbody>
                        <tr>
                            <td class="small-text"><label for="register_no"><strong>Register Number:</strong></label></td>
                            <td>
                                <input type="text" class="form-control" id="register_no" name="register_no" readonly>
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
                                <input type="checkbox" id="attendance" name="attendance" value="attended">
                                <label for="attendance">Attendance</label>
                            </td>
                        </tr>
                    </tbody>
                </table>

                <form id="marksForm">
                    <table class="table table-bordered" id="marksTable">
                        <thead>
                            <tr>
                                <th>Question No</th>
                                <th>Marks</th>
                                <th>Attended</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            // Create input fields based on the question count
                            for ($i = 1; $i <= $questionCount; $i++) {
                                echo "
                                <tr>
                                    <td>$i</td>
                                    <td><input type='number' class='form-control marks-input' name='marks[]' required></td>
                                    <td><input type='checkbox' name='attendance[]' value='attended' checked> Attended</td>
                                </tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                    <div class="mb-3">
                        <label for="total_mark">Total Mark:</label>
                        <input type="number" id="total_mark" class="form-control" readonly>
                    </div>
                    <button type="button" class="btn btn-info">Edit</button>
                    <button type="submit" class="btn btn-success">Save</button>
                    <button type="button" id="submitBtn" class="btn btn-primary">Submit</button>
                </form>

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
                <?php if (isset($_SESSION['success'])): ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <?php echo htmlspecialchars($_SESSION['success']); ?>
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <?php unset($_SESSION['success']); ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    // Function to calculate total marks automatically
    const marksInputs = document.getElementsByClassName('marks-input');

    for (let i = 0; i < marksInputs.length; i++) {
        marksInputs[i].addEventListener('input', function() {
            let total = 0;
            for (let j = 0; j < marksInputs.length; j++) {
                const mark = parseFloat(marksInputs[j].value) || 0;
                total += mark;
            }
            document.getElementById('total_mark').value = total;
        });
    }

    // Redirect on Submit button click
    document.getElementById('submitBtn').addEventListener('click', function() {
        // Logic to submit the form
        document.getElementById('marksForm').submit();
    });

    // Search functionality
    $('#searchBox').on('keyup', function() {
        const value = $(this).val().toLowerCase();
        $('#studentTable tr').filter(function() {
            $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
        });
    });
</script>

</body>
</html>
