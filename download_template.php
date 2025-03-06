<?php
session_start();
require 'db.php';
require 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

if (isset($_GET['year']) && isset($_GET['semester']) && isset($_GET['department'])) {
    $year = $_GET['year'];
    $semester = $_GET['semester'];
    $department = $_GET['department'];

    // Fetch subjects
    $stmt = $mysqli->prepare("SELECT subject_id, subject_name, subject_code FROM subjects WHERE semester = ? AND department = ?");
    if ($stmt === false) die('MySQL prepare error: ' . $mysqli->error);
    $stmt->bind_param('ss', $semester, $department);
    $stmt->execute();
    $subjects = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

    if (empty($subjects)) {
        die("No subjects found for the selected criteria.");
    }

    // Create a new Spreadsheet object
    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();

    // Set headers
    $headers = ['Register No', 'Student Name'];
    foreach ($subjects as $subject) {
        $headers[] = $subject['subject_code'];
    }
    $sheet->fromArray([$headers], NULL, 'A1');

    // Set file name and headers for download
    $filename = "grades_template_{$year}_{$semester}_{$department}.xlsx";
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment;filename="' . $filename . '"');
    header('Cache-Control: max-age=0');

    // Write file to output
    $writer = new Xlsx($spreadsheet);
    $writer->save('php://output');
    exit();
} else {
    die('Required parameters not set');
}
?>