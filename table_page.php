<?php
session_start();

// Retrieve the required parameters from the session
$year = isset($_SESSION['year']) ? $_SESSION['year'] : '';
$semester = isset($_SESSION['semester']) ? $_SESSION['semester'] : '';
$department = isset($_SESSION['department']) ? $_SESSION['department'] : '';
$test_type = isset($_SESSION['test_type']) ? $_SESSION['test_type'] : '';
$subject_name = isset($_SESSION['subject_name']) ? $_SESSION['subject_name'] : '';
$subject_code = isset($_SESSION['subject_code']) ? $_SESSION['subject_code'] : '';
$testmark = isset($_SESSION['testmark']) ? (int)$_SESSION['testmark'] : 0;

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Enter Marks and Count</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        #marksTable {
            margin-top: 20px;
            /* Space above the table */
            margin-bottom: 20px;
            /* Space below the table */
        }

        h2 {
            margin-top: 20px;
        }

        .details-table {
            margin-bottom: 30px;
            /* Space below details table */
        }

        .s {
            margin-bottom: 10px;
            margin-top: 20px;
            margin-right: 20px;

        }
    </style>
</head>

<body>

    <div class="container mt-5">
        <div class="row">
            <!-- Left Section: Previous Details -->
            <div class="col-md-12 details-table">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Year</th>
                            <th>Semester</th>
                            <th>Department</th>
                            <th>Test Type</th>
                            <th>Subject Name</th>
                            <th>Subject Code</th>
                            <th>Test Mark</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><?php echo htmlspecialchars($year); ?></td>
                            <td><?php echo htmlspecialchars($semester); ?></td>
                            <td><?php echo htmlspecialchars($department); ?></td>
                            <td><?php echo htmlspecialchars($test_type); ?></td>
                            <td><?php echo htmlspecialchars($subject_name); ?></td>
                            <td><?php echo htmlspecialchars($subject_code); ?></td>
                            <td><?php echo htmlspecialchars($testmark); ?></td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Left Section: Marks and Count -->
            <div class="col-md-6">
                <h2>Enter Marks and Count</h2>

                <!-- Table for marks and count -->
                <table class="table table-bordered" id="marksTable">
                    <thead>
                        <tr>
                            <th>S.No</th>
                            <th>Marks</th>
                            <th>Count</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>1</td>
                            <td><input type="number" class="form-control marks" name="marks[]" required></td>
                            <td><input type="number" class="form-control count" name="count[]" required></td>
                        </tr>
                    </tbody>
                </table>
                <!-- Display Total Marks -->
                <div class="mb-3">
                    <label for="total_mark">Total Mark:</label>
                    <input type="number" id="total_mark" class="form-control" readonly>
                </div>

                <!-- Add Row Button -->
                <button id="addRow" class="btn btn-secondary s">Add Row</button>


                <!-- Validate and Save -->
                <button id="validateBtn" class="btn btn-primary s">Save</button>

                <div id="errorMsg" class="text-danger mt-2"></div>
                <div id="successMsg" class="text-success mt-2" style="display: none;"></div>
            </div>

            <!-- Right Section: COs Table -->
            <div class="col-md-6">
                <!-- New Table for Question Numbers and Course Outcomes -->
                <div id="questionSection" class="mt-5" style="display: none;">
                    <h2>Enter Course Outcomes for Each Question</h2>
                    <table class="table table-bordered" id="questionsTable">
                        <thead>
                            <tr>
                                <th>Question No</th>
                                <th>Course Outcome</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Question rows will be dynamically added here -->
                        </tbody>
                    </table>

                    <button id="saveQuestions" class="btn btn-success">Save Questions</button>
                </div>
            </div>
        </div>
    </div>

    <!-- JavaScript to handle dynamic row addition and total mark calculation -->
    <script>
        const testmark = <?php echo $testmark; ?>;
        let sno = 1;

        // Function to dynamically add rows in the marks table
        document.getElementById('addRow').addEventListener('click', function() {
            sno++;
            const table = document.getElementById('marksTable').getElementsByTagName('tbody')[0];
            const newRow = table.insertRow();
            newRow.innerHTML = `
                <td>${sno}</td>
                <td><input type="number" class="form-control marks" name="marks[]" required></td>
                <td><input type="number" class="form-control count" name="count[]" required></td>
            `;
        });

        // Function to calculate total marks based on marks and count
        function calculateTotalMark() {
            let totalMark = 0;
            const marks = document.getElementsByClassName('marks');
            const counts = document.getElementsByClassName('count');

            for (let i = 0; i < marks.length; i++) {
                const mark = parseFloat(marks[i].value) || 0;
                const count = parseFloat(counts[i].value) || 0;
                totalMark += mark * count;
            }

            document.getElementById('total_mark').value = totalMark;
            return totalMark;
        }

        document.addEventListener('input', calculateTotalMark);

        // Function to generate the question table based on count
        function generateQuestionsTable() {
            const questionTableBody = document.getElementById('questionsTable').getElementsByTagName('tbody')[0];
            questionTableBody.innerHTML = ''; // Clear previous rows

            const counts = document.getElementsByClassName('count');
            let questionNo = 1;

            for (let i = 0; i < counts.length; i++) {
                const count = parseInt(counts[i].value) || 0;

                // Generate rows for each count
                for (let j = 0; j < count; j++) {
                    const newRow = questionTableBody.insertRow();
                    newRow.innerHTML = `
                        <td>${questionNo}</td>
                        <td>
                            <select class="form-select" name="course_outcome[]" required>
                                <option value="">--Select CO--</option>
                                <option value="CO1">CO1</option>
                                <option value="CO2">CO2</option>
                                <option value="CO3">CO3</option>
                                <option value="CO4">CO4</option>
                                <option value="CO5">CO5</option>
                                <option value="CO6">CO6</option>
                            </select>
                        </td>
                    `;
                    questionNo++;
                }
            }
        }

        // Validate button click event to validate total marks and generate question table
        document.getElementById('validateBtn').addEventListener('click', function() {
            const totalMark = calculateTotalMark();
            const errorMsg = document.getElementById('errorMsg');
            const successMsg = document.getElementById('successMsg');

            if (totalMark !== testmark) {
                errorMsg.innerText = `Total mark (${totalMark}) does not match Test Mark (${testmark}). Please check the values.`;
                successMsg.style.display = 'none'; // Hide success message
            } else {
                errorMsg.innerText = ''; // Clear error message
                successMsg.innerText = 'Marks are valid. Data saved successfully!';
                successMsg.style.display = 'block'; // Show success message

                // Generate COs table and show the question section
                generateQuestionsTable();
                document.getElementById('questionSection').style.display = 'block'; // Show the questions table
            }
        });

       // Save button click event for saving questions (you can handle further actions here)
document.getElementById('saveQuestions').addEventListener('click', function() {
    // Instead of alert, redirect to test_enter.php with the number of questions
    const questionTableBody = document.getElementById('questionsTable').getElementsByTagName('tbody')[0];
    const questions = questionTableBody.getElementsByTagName('tr');
    let questionCount = questions.length;

    // Use URL parameters to pass the question count
    window.location.href = `test_enter.php?questionCount=${questionCount}`;
});

    </script>
</body>

</html>