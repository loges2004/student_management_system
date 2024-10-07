<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>View Grades</title>
    <style>
        body {
            background-color: #f8f9fa;
        }
        .container {
            max-width: 700px;
            background-color: #ffffff;
            border-radius: 8px;
            padding: 30px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            margin-top: 50px;
        }
        h2, h4 {
            color: #343a40;
            font-weight: 600;
        }
        .profile-container {
            display: flex;
            justify-content: center;
            align-items: center;
            margin-bottom: 20px;
        }
        .profile-image {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid #007bff;
        }
        table {
            border-collapse: collapse;
            width: 100%;
        }
        thead {
            background-color: #007bff;
            color: #ffffff;
        }
        tbody tr:nth-child(even) {
            background-color: #f2f2f2;
        }
        tbody tr:nth-child(odd) {
            background-color: #e9ecef;
        }
        th, td {
            padding: 15px;
            text-align: center;
        }
        th {
            text-transform: uppercase;
            font-weight: bold;
        }
        .btn-close {
            float: right;
        }
        .btn-primary {
            background-color: #007bff;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            font-size: 16px;
        }
        .btn-danger {
            background-color: #dc3545;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            font-size: 16px;
        }
        .form-control {
            border-radius: 5px;
            padding: 10px;
            font-size: 16px;
        }
        .form-label {
            font-size: 18px;
            font-weight: 500;
        }
    </style>
    <script>
        function closeGrades() {
            document.getElementById('gradesSection').style.display = 'none';
        }
    </script>
</head>
<body>
    <div class="container">
        <h2 class="text-center mb-4">View Student Grades</h2>
        
        <!-- Form to Enter Register Number -->
        <form method="POST" action="">
            <div class="mb-3">
                <label for="register_no" class="form-label">Enter Register Number</label>
                <input type="text" class="form-control" id="register_no" name="register_no" placeholder="Register Number" required>
            </div>
            <div class="text-center">
                <button type="submit" class="btn btn-primary">View Grades</button>
            </div>
        </form>

        <?php
        session_start();
        require 'db.php';

        if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['register_no'])) {
            $register_no = $_POST['register_no'];

            // Fetch student details and grades from the database, including subject_code
            $stmt = $mysqli->prepare("
                SELECT DISTINCT s.student_name, s.department, g.grade, sub.subject_name, sub.subject_code, c.cgpa_mark, s.profile_image
                FROM stud s
                JOIN student_grades g ON s.student_id = g.student_id
                JOIN subjects sub ON g.subject_id = sub.subject_id
                JOIN cgpa_table c ON s.register_no = c.register_no
                WHERE s.register_no = ?
            ");
            
            if ($stmt === false) {
                die('MySQL prepare error: ' . $mysqli->error);
            }

            $stmt->bind_param('s', $register_no);
            $stmt->execute();
            $result = $stmt->get_result();
            $grades = $result->fetch_all(MYSQLI_ASSOC);
    
            // Fetch student info
            if (count($grades) > 0) {
                $studentName = htmlspecialchars($grades[0]['student_name']);
                $department = htmlspecialchars($grades[0]['department']);
                $profileImage = !empty($grades[0]['profile_image']) ? $grades[0]['profile_image'] : 'path/to/default/image.jpg';
            } else {
                die('No grades found for this register number.');
            }
        }
        ?>
    
        <?php if (isset($grades)): ?>
            <div id="gradesSection">
                <div class="profile-container">
                    <img src="<?= htmlspecialchars($profileImage); ?>" alt="Profile Image" class="profile-image">
                </div>
                <h2 class="text-center">Grades for <?= $studentName ?> (<?= $department ?>)</h2>
                <table class="table table-bordered mt-3">
                    <thead>
                        <tr>
                            <th>Subject Code</th>
                            <th>Subject Name</th>
                            <th>Grade</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($grades as $grade): ?>
                            <tr>
                                <td><?= htmlspecialchars($grade['subject_code']); ?></td>
                                <td><?= htmlspecialchars($grade['subject_name']); ?></td>
                                <td><?= htmlspecialchars($grade['grade']); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
    
                <h4 class="text-center mt-4">CGPA: <?= number_format($grades[0]['cgpa_mark'], 2); ?></h4>
                <div class="text-center mt-4">
                    <button class="btn btn-danger" onclick="closeGrades()">Close</button>
                </div>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>
