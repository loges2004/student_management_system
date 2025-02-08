<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Assessment Details</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>

    <div class="container mt-5">
        <h1 class="text-center">Enter Assessment Details</h1>

        <form action="validate_subject.php" method="POST">
            <!-- Staff ID -->
            <div class="mb-3">
                <label for="staff_id" class="form-label">Staff ID:</label>
                <input type="text" class="form-control" id="staff_id" name="staff_id" required>
            </div>

            <!-- Staff Name -->
            <div class="mb-3">
                <label for="staff_name" class="form-label">Staff Name:</label>
                <input type="text" class="form-control" id="staff_name" name="staff_name" required>
            </div>

            <!-- Year Selection -->
            <div class="mb-3">
                <label for="year" class="form-label">Select Year:</label>
                <select class="form-select" id="year" name="year" required>
                    <option value="">--Select Year--</option>
                    <option value="1">1st Year</option>
                    <option value="2">2nd Year</option>
                    <option value="3">3rd Year</option>
                    <option value="4">4th Year</option>
                </select>
            </div>

            <!-- Semester Selection -->
            <div class="mb-3">
                <label for="semester" class="form-label">Select Semester:</label>
                <select class="form-select" id="semester" name="semester" required>
                    <!-- Options will be populated dynamically via JavaScript -->
                </select>
            </div>

            <!-- Department Selection -->
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
            <!-- Section Selection -->
<div class="mb-3">
    <label for="section" class="form-label">Select Section:</label>
    <select name="section" id="section" class="form-select" required>
        <option value="">--Select Section--</option>
        <option value="A">Section A</option>
        <option value="B">Section B</option>
        <option value="C">Section C</option>
        <option value="D">Section D</option>
    </select>
</div>


            <!-- Subject Name -->
            <div class="mb-3">
                <label for="subject_name" class="form-label">Subject Name:</label>
                <input type="text" class="form-control" id="subject_name" name="subject_name" required>
            </div>

            <!-- Subject Code -->
            <div class="mb-3">
                <label for="subject_code" class="form-label">Subject Code:</label>
                <input type="text" class="form-control" id="subject_code" name="subject_code" required>
            </div>

            <!-- Test Type -->
            <div class="mb-3">
                <label for="test_type" class="form-label">Test Type:</label>
                <select class="form-select" id="test_type" name="test_type" required>
                    <option value="">--Select Test Type--</option>
                    <option value="serialtest1">Serial Test 1</option>
                    <option value="serialtest2">Serial Test 2</option>
                </select>
            </div>

            <!-- Test Mark -->
            <div class="mb-3">
                <label for="testmark" class="form-label">Select Test Mark:</label>
                <input type="number" name="testmark" id="testmark" class="form-control">
            </div>

            <!-- Pass Mark -->
            <div class="mb-3">
                <label for="passmark" class="form-label">Pass Mark:</label>
                <input type="number" name="passmark" class="form-control" id="passmark">
            </div>

            <!-- Submit Button -->
            <button type="submit" name="next" class="btn btn-primary">Save</button>
        </form>
    </div>

    <!-- JavaScript to handle dynamic semester options based on selected year -->
    <script>
        document.getElementById('year').addEventListener('change', function () {
            const year = this.value;
            const semester = document.getElementById('semester');
            semester.innerHTML = ''; // Clear previous options

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
