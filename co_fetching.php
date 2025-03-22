<?php
session_start();
include 'db.php'; 
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Academic Information</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .container {
            background-color: #ffffff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .form-label {
            font-weight: bold;
        }
        .table thead th {
            background-color: #007bff;
            color: white;
        }
        .table-striped tbody tr:nth-of-type(odd) {
            background-color: rgba(0, 123, 255, 0.05);
        }
        .btn-primary {
            background-color: #007bff;
            border-color: #007bff;
        }
        .btn-primary:hover {
            background-color: #0056b3;
            border-color: #004085;
        }
    </style>
</head>
<body>
    <div class="container mt-5">
        <div class="row">
            <div class="col-12">
                <h5 class="text-primary text-center mb-3">🎓 Academic Information</h5>
            </div>
            <form action="" method="POST">
                <div class="row g-3">
                    <div class="col-md-3">
                        <label for="year" class="form-label">Academic Year</label>
                        <select class="form-select" id="year" name="year" required>
                            <option value="">-- Select Year --</option>
                            <option value="1">1st Year</option>
                            <option value="2">2nd Year</option>
                            <option value="3">3rd Year</option>
                            <option value="4">4th Year</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label for="semester" class="form-label">Semester</label>
                        <select class="form-select" id="semester" name="semester" required>
                            <option value="">-- Select Semester --</option>
                            <option value="1">Semester 1</option>
                            <option value="2">Semester 2</option>
                            <option value="3">Semester 3</option>
                            <option value="4">Semester 4</option>
                            <option value="5">Semester 5</option>
                            <option value="6">Semester 6</option>
                            <option value="7">Semester 7</option>
                            <option value="8">Semester 8</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label for="department" class="form-label">Department</label>
                        <select class="form-select" id="department" name="department" required>
                            <option value="">-- Select Department --</option>
                            <option value="Information Technology">Information Technology</option>
                            <option value="Computer Science">Computer Science</option>
                            <option value="Mechanical">Mechanical</option>
                            <option value="Civil">Civil</option>
                            <option value="AIML">AIML</option>
                            <option value="Cyber Security">Cyber Security</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label for="section" class="form-label">Section</label>
                        <select class="form-select" id="section" name="section" required>
                            <option value="">-- Select Section --</option>
                            <option value="A">Section A</option>
                            <option value="B">Section B</option>
                            <option value="C">Section C</option>
                            <option value="D">Section D</option>
                        </select>
                    </div>
                    <div class="col-12 text-center mt-3">
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <?php

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Fetch form data
        $year = $_POST['year'];
        $semester = $_POST['semester'];
        $department = $_POST['department'];
        $section = $_POST['section'];

        // Fetch data from student_marks table
        $query = "
            SELECT register_no, student_name, 
                   SUM(CASE WHEN test_type = 'SERIALTEST1' AND course_outcome = 'CO1' THEN marks ELSE 0 END) AS SERIALTEST1_CO1,
                   SUM(CASE WHEN test_type = 'SERIALTEST1' AND course_outcome = 'CO2' THEN marks ELSE 0 END) AS SERIALTEST1_CO2,
                   SUM(CASE WHEN test_type = 'SERIALTEST1' AND course_outcome = 'CO3' THEN marks ELSE 0 END) AS SERIALTEST1_CO3,
                   SUM(CASE WHEN test_type = 'SERIALTEST1' AND course_outcome = 'CO4' THEN marks ELSE 0 END) AS SERIALTEST1_CO4,
                   SUM(CASE WHEN test_type = 'SERIALTEST1' AND course_outcome = 'CO5' THEN marks ELSE 0 END) AS SERIALTEST1_CO5,
                   SUM(CASE WHEN test_type = 'SERIALTEST2' AND course_outcome = 'CO1' THEN marks ELSE 0 END) AS SERIALTEST2_CO1,
                   SUM(CASE WHEN test_type = 'SERIALTEST2' AND course_outcome = 'CO2' THEN marks ELSE 0 END) AS SERIALTEST2_CO2,
                   SUM(CASE WHEN test_type = 'SERIALTEST2' AND course_outcome = 'CO3' THEN marks ELSE 0 END) AS SERIALTEST2_CO3,
                   SUM(CASE WHEN test_type = 'SERIALTEST2' AND course_outcome = 'CO4' THEN marks ELSE 0 END) AS SERIALTEST2_CO4,
                   SUM(CASE WHEN test_type = 'SERIALTEST2' AND course_outcome = 'CO5' THEN marks ELSE 0 END) AS SERIALTEST2_CO5
            FROM student_marks
            WHERE year = ? AND semester = ? AND department = ? AND section = ?
            GROUP BY register_no, student_name
        ";
        $stmt = $mysqli->prepare($query);
        if (!$stmt) {
            die('Error preparing statement: ' . $mysqli->error);
        }
        $stmt->bind_param("iiss", $year, $semester, $department, $section);
        $stmt->execute();
        $result = $stmt->get_result();

        // Display the results in a table
        echo '<div class="container mt-5">';
        echo '<h5 class="text-primary text-center mb-3">Student Marks</h5>';
        echo '<div class="table-responsive">';
        echo '<table class="table table-bordered table-striped">';
        echo '<thead>
                <tr>
                    <th class="text-center">Register No</th>
                    <th class="text-center">Student Name</th>
                    <th class="text-center" colspan="5">SERIALTEST1</th>
                    <th class="text-center" colspan="5">SERIALTEST2</th>
                </tr>
                <tr>
                    <th></th>
                    <th></th>
                    <th>CO1</th>
                    <th>CO2</th>
                    <th>CO3</th>
                    <th>CO4</th>
                    <th>CO5</th>
                    <th>CO1</th>
                    <th>CO2</th>
                    <th>CO3</th>
                    <th>CO4</th>
                    <th>CO5</th>
                </tr>
              </thead>
              <tbody>';

        while ($row = $result->fetch_assoc()) {
            echo '<tr>
                    <td>' . $row['register_no'] . '</td>
                    <td>' . $row['student_name'] . '</td>
                    <td>' . $row['SERIALTEST1_CO1'] . '</td>
                    <td>' . $row['SERIALTEST1_CO2'] . '</td>
                    <td>' . $row['SERIALTEST1_CO3'] . '</td>
                    <td>' . $row['SERIALTEST1_CO4'] . '</td>
                    <td>' . $row['SERIALTEST1_CO5'] . '</td>
                    <td>' . $row['SERIALTEST2_CO1'] . '</td>
                    <td>' . $row['SERIALTEST2_CO2'] . '</td>
                    <td>' . $row['SERIALTEST2_CO3'] . '</td>
                    <td>' . $row['SERIALTEST2_CO4'] . '</td>
                    <td>' . $row['SERIALTEST2_CO5'] . '</td>
                  </tr>';
        }

        echo '</tbody></table></div></div>';
        $stmt->close();
    }
    ?>
</body>
</html>