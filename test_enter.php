<?php
include('db.php');

session_start();

// Retrieve questionCount, marks, and counts from session or URL
if (isset($_GET['questionCount'], $_GET['marks'], $_GET['counts'])) {
    // If values are passed via URL, store them in the session
    $_SESSION['questionCount'] = (int)$_GET['questionCount'];
    $_SESSION['marksArray'] = json_decode(urldecode($_GET['marks']), true);
    $_SESSION['countsArray'] = json_decode(urldecode($_GET['counts']), true);
}

if (!isset($_SESSION['year'], $_SESSION['semester'], $_SESSION['department'], $_SESSION['section'], $_SESSION['test_type'], $_SESSION['subject_name'], $_SESSION['subject_code'])) {
    die("Session variables not set. Please configure the test first.");
}

// Fetch and sanitize session variables
$regulation = $_SESSION['regulation'] ?? '';
$year = $_SESSION['year'] ?? '';
$semester =strtoupper($_SESSION['semester']) ?? '';
$department = strtoupper($_SESSION['department']) ?? '';
$section =  strtoupper($_SESSION['section']) ?? '';
$test_type = strtoupper($_SESSION['test_type']) ?? '';
$subject_name = $_SESSION['subject_name'] ?? '';
$subject_code = $_SESSION['subject_code'] ?? '';
$testmark = $_SESSION['testmark'] ?? '';


// Retrieve question count from URL
$questionCount = isset($_GET['questionCount']) ? (int)$_GET['questionCount'] : 0;
$marksArray = $_SESSION['marksArray'] ?? [];
$countsArray = $_SESSION['countsArray'] ?? [];


// Fetch students and their marks
$students = [];
if (!empty($year) && !empty($department) && !empty($section)) {
    $query = "
    SELECT DISTINCT 
        s.register_no, 
        s.student_name, 
        s.section, 
        COALESCE(sm.total_marks, 'N/A') AS total_marks, 
        COALESCE(sm.attendance, 'N/A') AS attendance
    FROM stud s
    LEFT JOIN student_marks sm 
        ON s.register_no = sm.register_no
        AND sm.subject_code = ?
        AND sm.test_type = ?
    WHERE s.years = ? 
        AND UPPER(TRIM(s.department)) = UPPER(TRIM(?))
        AND UPPER(TRIM(s.section)) = UPPER(TRIM(?))
";
    $stmt = $mysqli->prepare($query);
    if ($stmt) {
        $stmt->bind_param(
            "ssiss",
            $subject_code,
            $test_type,
            $year,
            $department,
            $section
        );
        if (!$stmt->execute()) {
            die("Query execution failed: " . $stmt->error);
        }
        $result = $stmt->get_result();
        while ($row = $result->fetch_assoc()) {
            $students[] = $row;
        }
        $stmt->close();
    } else {
        die("Query preparation failed: " . $mysqli->error);
    }
} else {
    die("Year, department, or section is empty.");
}

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
        /* Custom Styles */
        body {
            background-color: #f8f9fa;
            font-family: 'Arial', sans-serif;
        }

        h2 {
            text-align: center;
            margin-top: 30px;
            margin-bottom: 30px;
            color: #343a40;
            font-weight: bold;
        }

        .form-section {
            background-color: #ffffff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .form-section:hover {
            transform: translateY(-5px);
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.15);
        }

        .table-responsive {
            max-height: 400px;
            overflow-y: auto;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .table thead th {
            background-color: #343a40;
            color: white;
            position: sticky;
            top: 0;
            z-index: 1;
        }

        .table tbody tr {
            transition: background-color 0.3s ease;
        }

        .table tbody tr:hover {
            background-color: #f1f1f1;
            cursor: pointer;
        }

        .btn {
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        .alert {
            position: fixed;
            top: 20px;
            left: 50%;
            transform: translateX(-50%);
            z-index: 1000;
            animation: fadeIn 0.5s ease;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
            }

            to {
                opacity: 1;
            }
        }

        .marks-input {
            transition: border-color 0.3s ease;
        }

        .marks-input:focus {
            border-color: #007bff;
            box-shadow: 0 0 5px rgba(0, 123, 255, 0.5);
        }

        .attendance-checkbox {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .attendance-checkbox input[type="checkbox"] {
            width: 20px;
            height: 20px;
        }

        /* Responsive Adjustments */
        @media (max-width: 768px) {
            .form-section {
                padding: 15px;
            }

            .table th,
            .table td {
                font-size: 0.9rem;
            }

            .btn {
                width: 100%;
                margin-bottom: 10px;
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
                <h4 class="text-center mb-3">Student Lists</h4>
                <div class="form-group">
                    <input type="text" id="searchBox" class="form-control " style="width:100%;" placeholder="Search by Register No or Student Name">
                </div>
                <div class="table-responsive mt-5" style="max-height: 600px; height: 400px; width: 550px; overflow-y: scroll; position: relative;">
    <table class="table table-bordered table-striped" id="studentTable">
        <thead class="table-dark" style="position: sticky; top: 0; z-index: 100;">
            <tr>
                <th class="text-center fw-bold">Register No</th>
                <th class="text-center fw-bold">Student Name</th>
                <th class="text-center fw-bold">Section</th>
                <th class="text-center fw-bold">Total Mark</th>
                <th class="text-center fw-bold">Attendance</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($students)) : ?>
                <?php foreach ($students as $student) : ?>
                    <tr class="student-row"
                        data-register-no="<?= htmlspecialchars($student['register_no']) ?>"
                        data-student-name="<?= htmlspecialchars($student['student_name']) ?>"
                        data-section="<?= htmlspecialchars($student['section']) ?>">
                        <td><?= htmlspecialchars($student['register_no']) ?></td>
                        <td><?= htmlspecialchars($student['student_name']) ?></td>
                        <td><?= htmlspecialchars($student['section']) ?></td>
                        <td><?= $student['total_marks'] === 'N/A' ?
                                '<span class="text-muted">N/A</span>' :
                                htmlspecialchars($student['total_marks']) ?></td>
                        <td><?= $student['attendance'] === 'N/A' ?
                                '<span class="text-muted">N/A</span>' :
                                htmlspecialchars($student['attendance']) ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php else : ?>
                <tr>
                    <td colspan="5" class="text-center text-danger">
                        No students found for selected criteria
                    </td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

                <button type="buttun" class="btn btn-danger me-5 mt-4 mb-5 w-100 text-center">Submit</button>

                <!-- Add this near the student list section -->
                <div class="col-md-12 mt-3">
                    <h4>Excel To Upload Marks</h4>
                    <form id="uploadForm" action="upload_marks.php" method="POST" enctype="multipart/form-data">
                        <div class="input-group mb-3">
                            <input type="file" class="form-control" name="excelFile" accept=".xlsx,.xls" required>
                            <input type="hidden" name="questionCount" value="<?= $questionCount ?>">
                            <input type="hidden" name="marks" value="<?= htmlspecialchars(json_encode($marksArray)) ?>">
                            <input type="hidden" name="counts" value="<?= htmlspecialchars(json_encode($countsArray)) ?>">
                            <button class="btn btn-primary" type="submit">Upload Excel</button>
                        </div>
                        <small class="text-muted">Download template <a href="#" id="downloadTemplate">here</a></small>
                    </form>
                </div>
            </div>

            <!-- Right Side: Marks Entry Form -->
            <div class="col-md-8">
                <table class="table table-sm table-bordered readonly-table" style="margin-left: 80px;">
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
                <div class="form-section" style="margin-left:80px ;">
                    <form id="marksForm" action="save_marks.php" method="POST">
                        <table class="table table-bordered ">
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
        <input type="checkbox" name="attendance_checkbox" id="attendance_checkbox" checked>
        <label for="attendance_checkbox">Attendance</label>
        <input type="hidden" name="attendance" id="attendance" value="Present"> <!-- Dynamic value -->
    </td>
</tr>
                            </tbody>
                        </table>

                        <input type="hidden" name="regulation" value="<?php echo htmlspecialchars($regulation); ?>">
                        <input type="hidden" name="year" value="<?php echo htmlspecialchars($year); ?>">
                        <input type="hidden" name="semester" value="<?php echo htmlspecialchars($semester); ?>">
                        <input type="hidden" name="department" value="<?php echo htmlspecialchars($department); ?>">
                        <input type="hidden" name="section" value="<?php echo htmlspecialchars($section); ?>">
                        <input type="hidden" name="test_type" value="<?php echo htmlspecialchars($test_type); ?>">
                        <input type="hidden" name="testmark" value="<?php echo htmlspecialchars($testmark); ?>">
                        <input type="hidden" name="subject_name" value="<?php echo htmlspecialchars($subject_name); ?>">
                        <input type="hidden" name="counts" value="<?php echo htmlspecialchars(json_encode($countsArray)); ?>">
                        <input type="hidden" name="original_marks" value="<?php echo htmlspecialchars(json_encode($marksArray)); ?>">
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
                        <button type="reset" class="btn btn-danger ms-4">cancel</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        // Pass the PHP arrays to JavaScript
        const marks = <?php echo json_encode($marksArray); ?>;
        const counts = <?php echo json_encode($countsArray); ?>;
        const questionCount = <?php echo json_encode($questionCount); ?>;

        // Function to set min and max attributes for marks input fields
        function setMarksInputAttributes() {
            let markIndex = 0; // Index for marksArray
            let count = 0; // Counter for countsArray

            // Loop through each question input field
            $('.marks-input').each(function(index) {
                // If the count exceeds the current count in countsArray, move to the next mark
                if (count >= counts[markIndex]) {
                    markIndex++;
                    count = 0; // Reset the counter
                }

                // Set min and max attributes for the current input field
                if (markIndex < marks.length) {
                    $(this).attr({
                        'min': 0,
                        'max': marks[markIndex]
                    });
                    count++; // Increment the counter
                }
            });
        }

        function resetMarksInputFields() {
            $('.marks-input').val(0).prop('disabled', true); // Reset all marks to 0 and disable them
            $('input[name^="attended"]').prop('checked', false); // Uncheck all attendance checkboxes
            $('#total_mark').val(0); // Reset total marks
        }

        $(document).ready(function() {
            // Set min and max attributes for marks input fields on page load
            setMarksInputAttributes();

            // Populate student details and marks when a row is clicked
            $(document).on('click', '.student-row', function() {
                const registerNo = $(this).data('register-no');
                const studentName = $(this).data('student-name');
                const section = $(this).data('section');

                $('#register_no').val(registerNo);
                $('#student_name').val(studentName);
                $('#section').val(section);

                // Fetch and populate marks for the selected student
                fetchStudentMarks(registerNo);
            });


            function fetchStudentMarks(registerNo) {
                const requestData = {
                    register_no: registerNo,
                    year: <?php echo json_encode($year); ?>,
                    semester: <?php echo json_encode($semester); ?>,
                    department: <?php echo json_encode($department); ?>,
                    section: <?php echo json_encode($section); ?>,
                    test_type: <?php echo json_encode($test_type); ?>,
                    subject_code: <?php echo json_encode($subject_code); ?>
                };

                console.log("Sending AJAX request with data:", requestData); // Debugging
                $.ajax({
                    url: 'fetch_student_marks.php',
                    type: 'GET',
                    data: requestData,
                    success: function(response) {
                        console.log("Raw Response:", response); // Debugging
                        console.log("Type of Response:", typeof response); // Debugging

                        if (response && response.success) {
                            // Ensure marks and total_marks are defined
                            let marks = response.marks !== undefined && response.marks !== null ? response.marks : 0;
                            let total_marks = response.total_marks !== undefined && response.total_marks !== null ? response.total_marks : 0;

                            // Populate form fields
                            populateMarks(marks, total_marks);
                        } else {
                            console.warn("Warning:", response.message || "No data found. Please enter the marks and save.");
                            showAlert("warning", response.message || "No data found. Please enter the marks and save.");
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error("Error:", error);
                        showAlert("danger", "Error fetching student marks.");
                        populateMarks(0, 0); // Ensure input fields are set to 0 even on AJAX error
                    }
                });
            }
            // Function to display Bootstrap 5 alert dynamically
            function showAlert(type, message) {
                let alertBox = `
        <div class="alert alert-${type} alert-dismissible fade show" role="alert" 
             style="position: fixed; top: 10px; left: 50%; transform: translateX(-50%); z-index: 1050;">
            <strong>${type === "danger" ? "Error" : "Warning"}:</strong> ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>`;

                $("body").append(alertBox); // Append the alert to the body

                // Remove the alert after 3 seconds
                setTimeout(() => {
                    $(".alert").fadeOut("slow", function() {
                        $(this).remove();
                    });
                }, 3000);
            }

            function populateMarks(marks, totalMarks) {
                // Reset all input fields to zero
                $('.marks-input').val(0);
                $('input[name^="attended"]').prop('checked', false);

                // Populate marks and attendance
                for (const [questionNumber, data] of Object.entries(marks)) {
                    $(`input[name="marks[${questionNumber}]"]`).val(data.marks);
                    $(`input[name="attended[${questionNumber}]"]`).prop('checked', data.attended === 1);
                }

                // Set total marks
                $('#total_mark').val(totalMarks);
            }
            // Calculate total marks dynamically
            $(document).on('input', '.marks-input', function() {
                let total = 0;
                $('.marks-input').each(function() {
                    total += parseFloat($(this).val()) || 0;
                });
                $('#total_mark').val(total);
            });

            // Handle attendance checkboxes
            $(document).on('change', 'input[name^="attended"]', function() {
                const questionNo = $(this).attr('name').match(/\d+/)[0];
                if (!$(this).is(':checked')) {
                    $(`input[name="marks[${questionNo}]"]`).val(0).prop('disabled', true);
                } else {
                    $(`input[name="marks[${questionNo}]"]`).prop('disabled', false).val('');
                }
            });

            $(document).ready(function() {
    // Handle the overall attendance checkbox
    $('#attendance_checkbox').on('change', function() {
        const isPresent = $(this).is(':checked');
        $('#attendance').val(isPresent ? 'Present' : 'Absent');

        if (!isPresent) {
            // If absent, set all marks to 0 and disable input fields
            $('.marks-input').val(0).prop('disabled', true);
            $('input[name^="attended"]').prop('checked', false).prop('disabled', true);
            $('#total_mark').val(0); // Set total marks to 0
        } else {
            // If present, enable input fields
            $('.marks-input').prop('disabled', false).val('');
            $('input[name^="attended"]').prop('disabled', false).prop('checked', true);
        }
    });

    // Ensure attendance value is set correctly before form submission
    $('#marksForm').on('submit', function() {
        const isPresent = $('#attendance_checkbox').is(':checked');
        $('#attendance').val(isPresent ? 'Present' : 'Absent');

        if (!isPresent) {
            // If absent, ensure all marks are 0
            $('.marks-input').val(0);
            $('#total_mark').val(0);
        }
    });
});

            // Function to enable marks input fields when attendance is checked
            function enableMarksInputFields() {
                $('.marks-input').prop('disabled', false).val(''); // Enable all marks input fields and clear 0 values
            }


            $(document).on('change', 'input[name="attendance"]', function() {
                const row = $(this).closest('tr'); // Get the row of the checkbox

                if (!$(this).is(':checked')) {
                    resetMarksInputFields(); // If unchecked, reset marks to 0 and disable input fields
                } else {
                    enableMarksInputFields(); // If checked, enable input fields
                }
            });

            // Search functionality
         $('#searchBox').on('keyup', function() {
    const value = $(this).val().toLowerCase();

    // Filter only tbody rows to ensure thead remains visible
    $('#studentTable tbody tr').each(function() {
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
        });

        // Automatically check attended checkbox when marks > 0
        $(document).on('input', '.marks-input', function() {
            const value = parseFloat($(this).val()) || 0;
            const checkbox = $(this).closest('tr').find('input[name^="attended"]');
            checkbox.prop('checked', value > 0);

            // Force 0 if invalid or negative value
            if (value <= 0) {
                $(this).val(0);
                checkbox.prop('checked', false);
            }
        });
        // Download template functionality
        document.getElementById('downloadTemplate').addEventListener('click', function() {
            // Create CSV headers
            let headers = ['Register No', 'Student Name'];
            for (let i = 1; i <= questionCount; i++) {
                headers.push(`Q${i}`);
            }
            headers.push('Total Mark');

            // Combine headers and sample row into CSV content
            const csvContent = headers.join(',') + '\n' + sampleRow.join(',');

            // Create a Blob and trigger the download
            const blob = new Blob([csvContent], {
                type: 'text/csv'
            });
            const link = document.createElement('a');
            link.href = URL.createObjectURL(blob);
            link.download = 'marks_template.csv';
            link.click();
        });
    </script>
</body>

</html>