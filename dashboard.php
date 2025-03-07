<?php
require 'db.php';  // Include database connection
session_start();  // Start session to access session variables

if (isset($_GET['staff_name'])) {
    $staff_name = htmlspecialchars($_GET['staff_name']);

}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Staff Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h1>Welcome, <?php echo $staff_name; ?>!</h1>
        <p>You are logged in as a staff member.</p>

        <!-- Display success message if it exists -->
        <?php if (isset($_SESSION['success'])): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <?php echo $_SESSION['success']; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            <?php unset($_SESSION['success']);  // Clear the message after displaying ?>
        <?php endif; ?>

        <!-- Display error message if it exists -->
        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <?php echo $_SESSION['error']; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            <?php unset($_SESSION['error']);  // Clear the message after displaying ?>
        <?php endif; ?>

        <!-- Example form to enter grades -->
        <form method="POST" action="enter_grades.php">
            <div class="mb-3">
                <label for="year" class="form-label">Select Year:</label>
                <select name="year" id="year" class="form-select" required>
                    <option value="">--Select Year--</option>
                    <option value="1">I Year</option>
                    <option value="2">II Year</option>
                    <option value="3">III Year</option>
                    <option value="4">IV Year</option>
                </select>
            </div>

            <div class="mb-3">
                <label for="semester" class="form-label">Select Semester:</label>
                <select name="semester" id="semester" class="form-select" required>
                    <!-- Options will be populated dynamically via JavaScript -->
                </select>
            </div>

            <div class="mb-3">
                <label for="department" class="form-label">Select Department:</label>
                <select name="department" id="department" class="form-select" required>
                    <option value="">--Select Department--</option>
                    <option value="Information Technology">Information Technology</option>
                    <option value="Computer Science">Computer Science</option>
                    <option value="Mechanical">Mechanical</option>
                    <option value="Civil">Civil</option>
                    <option value="AIML">AIML</option>
                    <option value="Cyber Security">Cyber Security</option>
                </select>
            </div>
            <div class="mb-3">
                        <label for="section" class="form-label">Section</label>
                        <select class="form-select" id="section" name="section" required>
                            <option value="">-- Select Section --</option>
                            <option value="A">Section A</option>
                            <option value="B">Section B</option>
                            <option value="C">Section C</option>
                            <option value="D">Section D</option>
                        </select>
                    </div>
            <button type="submit" class="btn btn-primary">Enter Grades</button>
        </form>
    </div>

    <!-- JavaScript to handle dynamic semester options based on selected year -->
    <script>
        document.getElementById('year').addEventListener('change', function () {
            const year = this.value;
            const semester = document.getElementById('semester');
            semester.innerHTML = '';  // Clear previous options

            if (year === '1') {
                semester.innerHTML += '<option value="1">Semester 1</option>';
                semester.innerHTML += '<option value="2">Semester 2</option>';
            } else if (year === '2') {
                semester.innerHTML += '<option value="3">Semester 3</option>';
                semester.innerHTML += '<option value="4">Semester 4</option>';
            } else if (year === '3') {
                semester.innerHTML += '<option value="5">Semester 5</option>';
                semester.innerHTML += '<option value="6">Semester 6</option>';
            } else if (year === '4') {
                semester.innerHTML += '<option value="7">Semester 7</option>';
                semester.innerHTML += '<option value="8">Semester 8</option>';
            }
        });
    </script>
</body>
</html>