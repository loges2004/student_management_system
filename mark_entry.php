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

            <div class="mb-3">
                <label for="semester" class="form-label">Select Semester:</label>
                <select class="form-select" id="semester" name="semester" required>
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
                <label for="subject_name" class="form-label">Subject Name:</label>
                <input type="text" class="form-control" id="subject_name" name="subject_name" required>
            </div>

            <div class="mb-3">
                <label for="subject_code" class="form-label">Subject Code:</label>
                <input type="text" class="form-control" id="subject_code" name="subject_code" required>
            </div>

            <div class="mb-3">
                <label for="testmark">Select Test Mark:</label>
                <select name="testmark" id="testmark" class="form-select" onchange="validateInput()">
                    <option value="">--Select--</option>
                    <option value="100">100</option>
                    <option value="60">60</option>
                </select>
            </div>

            <div class="mb-3">
                <label for="passmark">Pass Mark:</label>
                <input type="number" name="passmark" class="form-control" id="passmark" readonly>
            </div>

            <div class="mb-3">
                <label for="test_type" class="form-label">Test Type:</label>
                <select class="form-select" id="test_type" name="test_type" required>
                    <option value="">--Select Test Type--</option>
                </select>
            </div>

            <button type="submit" name="next" class="btn btn-primary">Next</button>
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

        function validateInput() {
            const testmarkField = document.getElementById('testmark');
            const passmarkField = document.getElementById('passmark');
            const testmarkValue = parseInt(testmarkField.value);
            const testTypeField = document.getElementById('test_type');

            // Clear previous test type options
            testTypeField.innerHTML = '<option value="">--Select Test Type--</option>';

            // Allow only 100 or 60 for testmark
            if (testmarkValue === 100) {
                passmarkField.value = 50; // Set passmark to 50
                // Add test type options for 100 marks
                testTypeField.innerHTML += '<option value="serial_test1">Serial Test 1</option>';
                testTypeField.innerHTML += '<option value="serial_test2">Serial Test 2</option>';
            } else if (testmarkValue === 60) {
                passmarkField.value = 30; // Set passmark to 30
                // Add test type options for 60 marks
                testTypeField.innerHTML += '<option value="serialtest1">Serial Test 1</option>';
                testTypeField.innerHTML += '<option value="serialtest2">Serial Test 2</option>';
            } else {
                alert('Only 100 and 60 are allowed in Test Mark.');
                testmarkField.value = ''; // Clear invalid testmark input
                passmarkField.value = ''; // Clear passmark input
            }
        }
    </script>

</body>

</html>
