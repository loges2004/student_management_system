<?php
session_start();
require 'db.php';
require 'vendor/autoload.php'; // Ensure you have PhpSpreadsheet installed via Composer

use PhpOffice\PhpSpreadsheet\IOFactory;

// Check if all required parameters are set
if (isset($_POST['year']) && isset($_POST['semester']) && isset($_POST['department']) && isset($_POST['section'])) {
    $year = $_POST['year'];
    $semester = $_POST['semester'];
    $department = $_POST['department'];
    $section = $_POST['section'];

    // Fetch subjects
    $stmt = $mysqli->prepare("SELECT subject_id, subject_name, subject_code, credit_points FROM subjects WHERE semester = ? AND department = ?");
    if ($stmt === false) die('MySQL prepare error: ' . $mysqli->error);
    $stmt->bind_param('ss', $semester, $department);
    $stmt->execute();
    $subjects = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

    if (empty($subjects)) {
        $_SESSION['error'] = "No subjects found for the selected criteria.";
        header("Location: dashboard.php");
        exit();
    }

    // Fetch students based on department, year, and section
    $student_query = "
    SELECT DISTINCT s.register_no, s.student_name, sg.cgpa_mark 
    FROM stud s 
    LEFT JOIN student_grades sg 
        ON s.register_no = sg.register_no 
        AND sg.semester = ? 
    WHERE TRIM(s.department) = TRIM(?) 
        AND s.years = ? 
        AND TRIM(s.section) = TRIM(?)
    ";
    $student_stmt = $mysqli->prepare($student_query) or die('MySQL prepare error: ' . $mysqli->error);
    $student_stmt->bind_param('ssss', $semester, $department, $year, $section);
    $student_stmt->execute();
    $students = $student_stmt->get_result()->fetch_all(MYSQLI_ASSOC);
} else {
    die('Required parameters not set');
}

// Handle Excel file upload
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['excel_file'])) {
    // Validate required parameters
    if (!isset($_POST['year']) || !isset($_POST['semester']) || !isset($_POST['department']) || !isset($_POST['section'])) {
        die('Required parameters not set.');
    }

    $year = $_POST['year'];
    $semester = $_POST['semester'];
    $department = $_POST['department'];
    $section = $_POST['section'];

    // Validate file upload
    if ($_FILES['excel_file']['error'] !== UPLOAD_ERR_OK) {
        die('File upload error: ' . $_FILES['excel_file']['error']);
    }

    // Load the Excel file
    $file = $_FILES['excel_file']['tmp_name'];
    try {
        $spreadsheet = IOFactory::load($file);
        $sheet = $spreadsheet->getActiveSheet();
        $rows = $sheet->toArray();

        // Assuming the first row is headers
        $headers = array_shift($rows);

        foreach ($rows as $row) {
            $register_no = $row[0];
            $student_name = $row[1];
            $grades = array_slice($row, 2); // Grades start from the third column

            // Insert or update grades for each subject
            foreach ($subjects as $index => $subject) {
                $subject_id = $subject['subject_id'];
                $grade = $grades[$index];

                $stmt = $mysqli->prepare("
                    INSERT INTO student_grades (register_no, student_name, subject_id, subject_code, subject_name, grade, semester, department, section, years) 
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
                    ON DUPLICATE KEY UPDATE 
                        student_name = VALUES(student_name),
                        subject_code = VALUES(subject_code),
                        subject_name = VALUES(subject_name),
                        grade = VALUES(grade), 
                        semester = VALUES(semester),
                        section = VALUES(section),
                        years = VALUES(years)
                ");

                if ($stmt === false) {
                    die('MySQL prepare error: ' . $mysqli->error);
                }

                $stmt->bind_param('ssissssssi', $register_no, $student_name, $subject_id, $subject['subject_code'], $subject['subject_name'], $grade, $semester, $department, $section, $year);
                $stmt->execute();
            }

            // Calculate CGPA based on grades and credit points
            $cgpa = 0;
            $credit_total = 0;
            $grade_points_total = 0;

            foreach ($subjects as $subject) {
                $subject_id = $subject['subject_id'];
                $grade = $grades[array_search($subject_id, array_column($subjects, 'subject_id'))];

                $credit_points = $subject['credit_points'];

                // Convert grade to grade points
                switch ($grade) {
                    case 'O': $grade_points = 10; break;
                    case 'A+': $grade_points = 9; break;
                    case 'A': $grade_points = 8; break;
                    case 'B+': $grade_points = 7; break;
                    case 'B': $grade_points = 6; break;
                    case 'C': $grade_points = 5; break;
                    default: $grade_points = 0; break;
                }

                $grade_points_total += $grade_points * $credit_points;
                $credit_total += $credit_points;
            }

            if ($credit_total > 0) {
                $cgpa = $grade_points_total / $credit_total;
            }

            $truncated_cgpa = floor($cgpa * 100) / 100;

            // Update CGPA in all rows for the student in the current semester
            $stmt = $mysqli->prepare("
                UPDATE student_grades 
                SET cgpa_mark = ? 
                WHERE register_no = ? 
                AND semester = ? 
                AND department = ? 
                AND section = ?
                AND years = ?
            ");

            if ($stmt === false) {
                die('MySQL prepare error: ' . $mysqli->error);
            }

            $stmt->bind_param('dsssss', $truncated_cgpa, $register_no, $semester, $department, $section, $year);
            if (!$stmt->execute()) {
                die('MySQL execute error: ' . $stmt->error);
            }
        }

        $_SESSION['success'] = "Grades uploaded successfully.";
        header("Location: enter_grades.php");
        exit();
    } catch (Exception $e) {
        die('Error loading Excel file: ' . $e->getMessage());
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <title>Enter Grades</title>
    <style>
        :root {
            --primary-color: #2a2a72;
            --secondary-color: #009ffd;
        }
        body {
            background-color: #f8f9fa;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }
        .main-container {
            flex: 1;
            padding: 20px;
        }
        .student-list-container {
            height: 70vh;
            overflow-y: auto;
            border: 1px solid #dee2e6;
            border-radius: 8px;
            background: white;
        }
        .grade-form-container {
            background: white;
            border-radius: 8px;
            padding: 20px;
            box-shadow: 0 0 15px rgba(0,0,0,0.05);
        }
        .profile-image {
            width: 80px;
            height: 80px;
            object-fit: cover;
            border: 2px solid var(--primary-color);
        }
        .table-hover tbody tr:hover {
            background-color: rgba(0,123,255,0.1);
            cursor: pointer;
        }
        @media (max-width: 768px) {
            .student-list-container {
                height: 40vh;
                margin-bottom: 20px;
            }
            .profile-image {
                width: 60px;
                height: 60px;
            }
        }
    </style>
</head>
<body>
    <div class="main-container container">
        <h2 class="text-center mb-4">Enter Grades - <?= htmlspecialchars($department) ?> (Sem <?= htmlspecialchars($semester) ?>)</h2>
        
        <div class="row g-4">
            <!-- Student List -->
            <div class="col-lg-4">
                <div class="student-list-container p-3">
                    <div class="input-group mb-3">
                        <input type="text" id="search" class="form-control" placeholder="Search students...">
                    </div>
                    <table class="table table-hover">
                        <thead class="bg-primary text-white">
                            <tr>
                                <th>Reg No</th>
                                <th>Name</th>
                                <th>CGPA</th>
                            </tr>
                        </thead>
                        <tbody id="studentTableBody">
                            <?php foreach ($students as $student): ?>
                                <tr data-register-no="<?= htmlspecialchars($student['register_no']) ?>">
                                    <td><?= htmlspecialchars($student['register_no']) ?></td>
                                    <td><?= htmlspecialchars($student['student_name']) ?></td>
                                    <td><?= htmlspecialchars($student['cgpa_mark'] ?? 'N/A') ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Grade Form -->
            <div class="col-lg-8">
                <div class="grade-form-container">
                    <form method="POST" action="save_grades.php" id="gradesForm">
                        <input type="hidden" name="year" value="<?= htmlspecialchars($year) ?>">
                        <input type="hidden" name="semester" value="<?= htmlspecialchars($semester) ?>">
                        <input type="hidden" name="section" value="<?= htmlspecialchars($section) ?>">

                        <div class="row g-3 mb-4 align-items-center">
                            <div class="col-md-6">
                                <label class="form-label">Register No</label>
                                <input type="text" class="form-control" id="register_no" name="register_no" >
                            </div>
                            <!-- Profile Image -->
                            <div class="col-md-6 text-md-end">
                                <img id="profile_image" src="./images/default_profile.jpg" class="profile-image" alt="Student Photo">
                            </div>
                            <div class="col-md-12">
                                <label class="form-label">Student Name</label>
                                <input type="text" class="form-control" id="student_name" name="student_name">
                            </div>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead class="bg-primary text-white">
                                    <tr>
                                        <th>Subject Code</th>
                                        <th>Subject Name</th>
                                        <th>Grade</th>
                                    </tr>
                                </thead>
                                <tbody>
                                <?php foreach ($subjects as $subject): ?>
                                    <tr>
                                        <td><?= htmlspecialchars($subject['subject_code']) ?></td>
                                        <td><?= htmlspecialchars($subject['subject_name']) ?></td>
                                        <td>
                                            <input type="text" class="form-control" 
                                                   name="grades[<?= $subject['subject_id'] ?>]" 
                                                   required>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>

                        <div class="d-grid mt-4">
                            <button type="submit" class="btn btn-primary btn-lg">Save Grades</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

       <!-- Excel Upload Section -->
<div class="row mt-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h5 class="card-title mb-0">Upload Grades via Excel</h5>
            </div>
            <div class="card-body">
                <form method="POST" enctype="multipart/form-data">
                    <!-- Include required parameters as hidden inputs -->
                    <input type="hidden" name="year" value="<?= htmlspecialchars($year) ?>">
                    <input type="hidden" name="semester" value="<?= htmlspecialchars($semester) ?>">
                    <input type="hidden" name="department" value="<?= htmlspecialchars($department) ?>">
                    <input type="hidden" name="section" value="<?= htmlspecialchars($section) ?>">

                    <div class="mb-3">
                        <label for="excel_file" class="form-label">Choose Excel File</label>
                        <input type="file" class="form-control" id="excel_file" name="excel_file" accept=".xlsx, .xls" required>
                    </div>
                    <button type="submit" class="btn btn-success">Upload</button>
                    <a href="download_template.php?year=<?= $year ?>&semester=<?= $semester ?>&department=<?= $department ?>" class="btn btn-secondary">Download Template</a>
                </form>
            </div>
        </div>
    </div>
</div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        $(document).ready(function() {
            // Student row click handler
            $('#studentTableBody tr').click(function() {
                const registerNo = $(this).data('register-no');
                const studentName = $(this).find('td:nth-child(2)').text();

                // Populate register_no and student_name
                $('#register_no').val(registerNo);
                $('#student_name').val(studentName);

                // Fetch grades and profile image via AJAX
                $.ajax({
                    url: 'fetch_student_grades.php',
                    type: 'GET',
                    data: {
                        register_no: registerNo,
                        semester: <?= $semester ?>,
                        department: '<?= trim($department) ?>' // Ensure no leading/trailing spaces
                    },
                    success: function(response) {
                        console.log("AJAX Response:", response);
                        const data = JSON.parse(response);

                        // Clear all grade inputs
                        $('input[name^="grades"]').val('');

                        // Populate grades
                        if (data.grades && data.grades.length > 0) {
                            data.grades.forEach(grade => {
                                const inputField = $(`input[name="grades[${grade.subject_id}]"]`);
                                if (inputField.length) {
                                    inputField.val(grade.grade);
                                } else {
                                    console.warn(`Input field for subject_id ${grade.subject_id} not found.`);
                                }
                            });
                        } else {
                            console.warn("No grades found for this student.");
                        }

                        // Set profile image
                        if (data.profile_image) {
                            $('#profile_image').attr('src', `./${data.profile_image}`);
                        } else {
                            $('#profile_image').attr('src', './images/default_profile.jpg');
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error("AJAX Error:", status, error);
                        Swal.fire('Error', 'Failed to fetch student data.', 'error');
                    }
                });
            });
        });

        $(document).ready(function() {
        <?php if (isset($_SESSION['success'])): ?>
            Swal.fire({
                icon: 'success',
                title: 'Success',
                text: '<?= $_SESSION['success'] ?>'
            });
            <?php unset($_SESSION['success']); ?>
        <?php endif; ?>

        <?php if (isset($_SESSION['error'])): ?>
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: '<?= $_SESSION['error'] ?>'
            });
            <?php unset($_SESSION['error']); ?>
        <?php endif; ?>
    }); 
    </script>
</body>
</html>