<?php
require 'db.php';  // Include database connection
session_start();  // Start session to access session variables

// Get the logged-in staff member's email from session
$email = $_SESSION['email'];

// Fetch the staff member's firstname and lastname from the database
$query = "SELECT firstname, lastname FROM students WHERE email = '$email' AND selectusertype = 'staff'";
$result = mysqli_query($mysqli, $query);

// Check if the query was successful and returned a result
if ($result && mysqli_num_rows($result) > 0) {
    $row = mysqli_fetch_assoc($result);  // Fetch the result as an associative array

    // Concatenate firstname and lastname
    $staff_name = $row['firstname'] . ' ' . $row['lastname'];
} else {
    // If no result found, handle it (optional)
    echo "Staff member not found.";
    exit();
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
        <!-- Add additional dashboard content here -->

        <!-- Example form to enter grades -->
        <form method="POST" action="enter_grades.php">
            <div class="mb-3">
                <label for="year" class="form-label">Select Year:</label>
                <select name="year" id="year" class="form-select" required>
                    <option value="">--Select Year--</option>
                    <option value="I">I Year</option>
                    <option value="II">II Year</option>
                    <option value="III">III Year</option>
                    <option value="IV">IV Year</option>
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

            <button type="submit" class="btn btn-primary">Enter Grades</button>
        </form>
    </div>

    <!-- JavaScript to handle dynamic semester options based on selected year -->
    <script>
        document.getElementById('year').addEventListener('change', function () {
            const year = this.value;
            const semester = document.getElementById('semester');
            semester.innerHTML = '';  // Clear previous options

            if (year === 'I') {
                semester.innerHTML += '<option value="1">Semester 1</option>';
                semester.innerHTML += '<option value="2">Semester 2</option>';
            } else if (year === 'II') {
                semester.innerHTML += '<option value="3">Semester 3</option>';
                semester.innerHTML += '<option value="4">Semester 4</option>';
            } else if (year === 'III') {
                semester.innerHTML += '<option value="5">Semester 5</option>';
                semester.innerHTML += '<option value="6">Semester 6</option>';
            } else if (year === 'IV') {
                semester.innerHTML += '<option value="7">Semester 7</option>';
                semester.innerHTML += '<option value="8">Semester 8</option>';
            }
        });
    </script>
</body>
</html>
