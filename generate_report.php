<?php
session_start();
require 'db.php';

// Fetch parameters from the URL
$year = $_GET['year'] ?? die('Year not provided.');
$semester = $_GET['semester'] ?? die('Semester not provided.');
$department = $_GET['department'] ?? die('Department not provided.');
$section = $_GET['section'] ?? die('Section not provided.');

// Fetch regulation from the stud table
$regulation_query = "SELECT regulation FROM stud WHERE department = ? AND years = ? AND section = ? LIMIT 1";
$regulation_stmt = $mysqli->prepare($regulation_query);
if ($regulation_stmt === false) die('MySQL prepare error: ' . $mysqli->error);
$regulation_stmt->bind_param('sis', $department, $year, $section);
$regulation_stmt->execute();
$regulation_result = $regulation_stmt->get_result();
$regulation_row = $regulation_result->fetch_assoc();
$regulation = $regulation_row['regulation'] ?? 'N/A';

// Fetch subjects for the selected criteria
$stmt = $mysqli->prepare("SELECT subject_id, subject_code, subject_name FROM subjects WHERE department = ? AND years = ? AND semester = ?");
if ($stmt === false) die('MySQL prepare error: ' . $mysqli->error);
$stmt->bind_param('sii', $department, $year, $semester);
$stmt->execute();
$subjects = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

if (empty($subjects)) {
    die("No subjects found for the selected criteria.");
}

// Get all students and their grades
$query = "SELECT s.register_no, s.student_name, ";
foreach ($subjects as $subject) {
    $query .= "MAX(CASE WHEN sg.subject_code = '{$subject['subject_code']}' THEN sg.grade END) AS {$subject['subject_code']}, ";
}
$query .= "sg.cgpa_mark
          FROM stud s
          JOIN student_grades sg ON s.register_no = sg.register_no
          WHERE s.department = ? AND s.years = ? AND s.section = ?
          GROUP BY s.register_no, s.student_name
          ORDER BY s.register_no";

$stmt = $mysqli->prepare($query);
$stmt->bind_param('sis', $department, $year, $section);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $students = [];
    $subjectGrades = [];

    // Initialize subject grades array
    foreach ($subjects as $subject) {
        $subjectGrades[$subject['subject_code']] = [];
    }

    $arrearsCount = [1 => 0, 2 => 0, 3 => 0];
    $allPassCount = 0;

    while ($row = $result->fetch_assoc()) {
        $arrears = 0;
        foreach ($subjects as $subject) {
            $subject_code = $subject['subject_code'];
            if ($row[$subject_code] == 'U') $arrears++;
            $subjectGrades[$subject_code][] = $row[$subject_code];
        }
        if ($arrears == 0) $allPassCount++;
        elseif ($arrears >= 1 && $arrears <= 3) $arrearsCount[$arrears]++;
        
        $students[] = $row;
    }

    // Calculate subject statistics
    $subjectStats = [];
    foreach ($subjectGrades as $code => $grades) {
        $stats = [
            'O' => 0, 'A+' => 0, 'A' => 0, 'B+' => 0,
            'B' => 0, 'C' => 0, 'U' => 0, 'WH1' => 0
        ];
        foreach ($grades as $grade) {
            $stats[$grade] = isset($stats[$grade]) ? $stats[$grade] + 1 : 1;
        }
        $total = count($grades);
        
        // Check if $total is greater than zero to avoid division by zero
        if ($total > 0) {
            $passPercent = (($total - $stats['U']) / $total) * 100;
        } else {
            $passPercent = 0; // Set pass percentage to 0 if no grades are available
        }
        
        $subjectStats[$code] = [
            'grades' => $stats,
            'pass_percent' => round($passPercent, 2),
            'failures' => $stats['U']
        ];
    }

    // Calculate overall statistics
    $totalStudents = count($students);
    $overallPassPercent = ($allPassCount / $totalStudents) * 100;
} else {
    echo "No grades found for the selected criteria.";
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Academic Report</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        .header { text-align: center; margin-bottom: 20px; }
        .bold { font-weight: bold; }
        .subject-header td { background-color: #f0f0f0; }
        canvas { max-width: 100%; height: auto; }
        .signature { margin-top: 50px; }
        .signature .left { float: left; }
        .signature .right { float: right; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2>PSNA College of Engineering and Technology, Dindigul</h2>
            <h3>Department of Information Technology</h3>
            <p>Year: <?= $year ?> &nbsp;&nbsp;&nbsp;&nbsp; Sem: <?= $semester ?> &nbsp;&nbsp;&nbsp;&nbsp; Section: <?= $section ?></p>
            <h4>End Semester Result Analysis (Regulation: <?= $regulation ?>)</h4>
        </div>

        <!-- Download PDF Button -->
        <div class="text-end mb-3">
            <button id="downloadPdf" class="btn btn-primary">Download PDF</button>
        </div>

        <!-- Subject Header -->
        <table class="table table-bordered">
            <tr class="subject-header">
                <td>Subject Code</td>
                <?php foreach ($subjects as $subject): ?>
                    <td><?= $subject['subject_code'] ?></td>
                <?php endforeach; ?>
                <td>No. of Arrears</td>
            </tr>
            <tr class="subject-header">
                <td>Subject Name</td>
                <?php foreach ($subjects as $subject): ?>
                    <td><?= $subject['subject_name'] ?></td>
                <?php endforeach; ?>
                <td></td>
            </tr>
        </table>

        <!-- Student Grades Table -->
        <table class="table table-bordered">
            <tr>
                <th>S.No.</th>
                <th>Reg. No.</th>
                <th>Name</th>
                <?php foreach ($subjects as $subject): ?>
                    <th><?= $subject['subject_code'] ?></th>
                <?php endforeach; ?>
                <th>Arrears</th>
            </tr>
            <?php foreach ($students as $index => $student): ?>
            <tr>
                <td><?= $index + 1 ?></td>
                <td><?= $student['register_no'] ?></td>
                <td><?= $student['student_name'] ?></td>
                <?php 
                $arrears = 0;
                foreach ($subjects as $subject) {
                    $subject_code = $subject['subject_code'];
                    echo "<td>" . $student[$subject_code] . "</td>";
                    if ($student[$subject_code] == 'U') $arrears++;
                }
                ?>
                <td><?= $arrears > 0 ? $arrears : '' ?></td>
            </tr>
            <?php endforeach; ?>
        </table>

        <!-- Summary Statistics -->
        <table class="table table-bordered">
            <tr class="bold">
                <td>No. of Failures</td>
                <?php foreach ($subjects as $subject): ?>
                    <td><?= $subjectStats[$subject['subject_code']]['failures'] ?></td>
                <?php endforeach; ?>
                <td><?= array_sum(array_column($subjectStats, 'failures')) ?></td>
            </tr>
            <tr class="bold">
                <td>Subject Wise %</td>
                <?php foreach ($subjects as $subject): ?>
                    <td><?= $subjectStats[$subject['subject_code']]['pass_percent'] ?></td>
                <?php endforeach; ?>
                <td></td>
            </tr>
            <tr class="bold">
                <td>Overall %</td>
                <td colspan="<?= count($subjects) + 1 ?>"><?= round($overallPassPercent, 2) ?></td>
            </tr>
        </table>

        <!-- Arrears Summary -->
        <table class="table table-bordered">
            <tr>
                <td colspan="2" class="bold">All Pass</td>
                <td><?= $allPassCount ?></td>
            </tr>
            <tr>
                <td colspan="2" class="bold">1 Subject Arrear</td>
                <td><?= $arrearsCount[1] ?></td>
            </tr>
            <tr>
                <td colspan="2" class="bold">2 Subjects Arrear</td>
                <td><?= $arrearsCount[2] ?></td>
            </tr>
            <tr>
                <td colspan="2" class="bold">3 Subjects Arrear</td>
                <td><?= $arrearsCount[3] ?></td>
            </tr>
        </table>

        <!-- Grade Distribution -->
        <table class="table table-bordered">
            <tr>
                <th>Grade</th>
                <?php foreach ($subjects as $subject): ?>
                    <th><?= $subject['subject_code'] ?></th>
                <?php endforeach; ?>
            </tr>
            <?php foreach (['O', 'A+', 'A', 'B+', 'B', 'C', 'U', 'WH1'] as $grade): ?>
            <tr>
                <td class="bold"><?= $grade ?></td>
                <?php foreach ($subjects as $subject): ?>
                    <td><?= $subjectStats[$subject['subject_code']]['grades'][$grade] ?? 0 ?></td>
                <?php endforeach; ?>
            </tr>
            <?php endforeach; ?>
        </table>

        <!-- Graphs -->
        <div class="row mt-4">
            <div class="col-md-6">
                <canvas id="passFailChart"></canvas>
            </div>
            <div class="col-md-6">
                <canvas id="gradeDistributionChart"></canvas>
            </div>
        </div>

        <!-- Signature Section -->
        <div class="signature">
            <div class="left">
                <p>Class Incharge</p>
                <p>Signature: ___________________</p>
            </div>
            <div class="right">
                <p>HOD</p>
                <p>Signature: ___________________</p>
            </div>
        </div>
    </div>

    <script>
        // Pass/Fail Chart
        const passFailCtx = document.getElementById('passFailChart').getContext('2d');
        const passFailChart = new Chart(passFailCtx, {
            type: 'pie',
            data: {
                labels: ['Pass', 'Fail'],
                datasets: [{
                    data: [<?= $allPassCount ?>, <?= $totalStudents - $allPassCount ?>],
                    backgroundColor: ['#4CAF50', '#F44336']
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    title: {
                        display: true,
                        text: 'Pass/Fail Distribution'
                    }
                }
            }
        });

        // Grade Distribution Chart
        const gradeDistributionCtx = document.getElementById('gradeDistributionChart').getContext('2d');
        const gradeDistributionChart = new Chart(gradeDistributionCtx, {
            type: 'bar',
            data: {
                labels: ['O', 'A+', 'A', 'B+', 'B', 'C', 'U', 'WH1'],
                datasets: [
                    <?php
                    $subjectCount = count($subjects);
                    foreach ($subjects as $index => $subject):
                        $subjectCode = $subject['subject_code'];
                        $grades = $subjectStats[$subjectCode]['grades'] ?? [
                            'O' => 0,
                            'A+' => 0,
                            'A' => 0,
                            'B+' => 0,
                            'B' => 0,
                            'C' => 0,
                            'U' => 0,
                            'WH1' => 0
                        ];
                    ?>
                    {   label: '<?= $subjectCode ?>',
                        data: [
                            <?= $grades['O'] ?>,
                            <?= $grades['A+'] ?>,
                            <?= $grades['A'] ?>,
                            <?= $grades['B+'] ?>,
                            <?= $grades['B'] ?>,
                            <?= $grades['C'] ?>,
                            <?= $grades['U'] ?>,
                            <?= $grades['WH1'] ?>
                        ],
                        backgroundColor: 'rgba(54, 162, 235, 0.2)',
                        borderColor: 'rgba(54, 162, 235, 1)',
                        borderWidth: 1
                    }<?= ($index < $subjectCount - 1) ? ',' : ''; ?>
                    <?php endforeach; ?>
                ]
            },
            options: {
                responsive: true,
                plugins: {
                    title: {
                        display: true,
                        text: 'Grade Distribution'
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });

        // Download PDF
        document.getElementById('downloadPdf').addEventListener('click', () => {
            const { jsPDF } = window.jspdf;
            const doc = new jsPDF();

            // Add content to PDF
            doc.text("Academic Report", 10, 10);
            doc.text(`Year: ${<?= $year ?>}`, 10, 20);
            doc.text(`Semester: ${<?= $semester ?>}`, 10, 30);
            doc.text(`Section: ${<?= $section ?>}`, 10, 40);
            doc.text(`Regulation: ${<?= $regulation ?>}`, 10, 50);

            // Save PDF
            doc.save('academic_report.pdf');
        });
    </script>
</body>
</html>