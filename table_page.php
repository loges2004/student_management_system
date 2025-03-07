<?php
session_start();

// Retrieve the required parameters from the session
$staff_id = isset($_SESSION['staff_id']) ? $_SESSION['staff_id'] : '';
$regulation = isset($_SESSION['regulation']) ? $_SESSION['regulation'] : '';
$staff_name = isset($_SESSION['staff_name']) ? $_SESSION['staff_name'] : '';
$year = isset($_SESSION['year']) ? $_SESSION['year'] : '';
$semester = isset($_SESSION['semester']) ? $_SESSION['semester'] : '';
$department = isset($_SESSION['department']) ? $_SESSION['department'] : '';
$section = isset($_SESSION['section']) ? $_SESSION['section'] : '';
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
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.3/font/bootstrap-icons.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
    <style>
        :root {
            --primary-color: #2c3e50;
            --secondary-color:rgb(47, 105, 146);
            --accent-color: #e74c3c;
            --hover-color:rgb(127, 190, 233);
            --background-color: #f8f9fa;
        }

        body {
            background-color: var(--background-color);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .container {
            padding: 2rem;
            animation: fadeIn 0.5s ease-in;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .details-table {
            background: white;
            border-radius: 15px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            margin-bottom: 2rem;
            transition: transform 0.3s ease;
        }

        .details-table:hover {
            transform: translateY(-5px);
        }

        .table thead {
            background-color: var(--primary-color);
            color: white;
        }

        .table th {
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.9rem;
        }

        .table tbody tr {
            transition: background-color 0.2s ease;
        }

        .table tbody tr:hover {
            background-color: #f8f9fa;
        }

        .btn {
            transition: all 0.3s ease;
            font-weight: 500;
            letter-spacing: 0.5px;
            position: relative;
            overflow: hidden;
        }

        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
        }

        .btn-primary {
            background-color: var(--secondary-color);
            border: none;
        }

        .btn-primary:hover {
            background-color: var(--hover-color);
        }

        .btn-secondary {
            background-color: #95a5a6;
            border: none;
        }

        .btn-danger {
            background-color: var(--accent-color);
            border: none;
        }

        #marksTable {
            background: white;
            border-radius: 10px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
        }

        .form-control {
            border: 2px solid #ecf0f1;
            transition: border-color 0.3s ease;
        }

        .form-control:focus {
            border-color: var(--secondary-color);
            box-shadow: none;
        }

        .total-mark-container {
            background: var(--secondary-color);
            color: white;
            padding: 1rem;
            border-radius: 8px;
            margin: 1rem 0;
            animation: slideUp 0.4s ease-out;
        }

        @keyframes slideUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        #questionSection {
            animation: scaleIn 0.4s ease-out;
        }

        @keyframes scaleIn {
            from { transform: scale(0.95); opacity: 0; }
            to { transform: scale(1); opacity: 1; }
        }

        .sticky-section {
            position: sticky;
            top: 20px;
        }

        @media (max-width: 768px) {
            .container {
                padding: 1rem;
            }

            .details-table {
                margin-bottom: 1rem;
            }

            .row > div {
                margin-bottom: 1.5rem;
            }

            .btn {
                width: 100%;
                margin-bottom: 10px;
            }

            .table-responsive {
                overflow-x: auto;
            }
        }

        .success-pulse {
            animation: pulse 1.5s infinite;
        }

        @keyframes pulse {
            0% { box-shadow: 0 0 0 0 rgba(52, 152, 219, 0.5); }
            70% { box-shadow: 0 0 0 10px rgba(52, 152, 219, 0); }
            100% { box-shadow: 0 0 0 0 rgba(52, 152, 219, 0); }
        }

        .floating-alert {
            position: fixed;
            bottom: 20px;
            right: 20px;
            z-index: 1000;
            animation: slideIn 0.5s ease-out;
        }

        @keyframes slideIn {
            from { transform: translateX(100%); }
            to { transform: translateX(0); }
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
                            <th class="text-center">Staff Name</th>
                            <th class="text-center">regulation</th>
                            <th class="text-center">Year</th>
                            <th class="text-center">Semester</th>
                            <th class="text-center">Department</th>
                            <th class="text-center">Section</th>
                            <th class="text-center">Test Type</th>
                            <th class="text-center">Subject Name</th>
                            <th class="text-center">Subject Code</th>
                            <th class="text-center">Test Mark</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="text-center"><?php echo htmlspecialchars($staff_name); ?></td>
                            <td class="text-center"><?php echo htmlspecialchars($regulation); ?></td>
                            <td class="text-center"><?php echo htmlspecialchars($year); ?></td>
                            <td class="text-center"><?php echo htmlspecialchars($semester); ?></td>
                            <td class="text-center"><?php echo htmlspecialchars($department); ?></td>
                            <td class="text-center"><?php echo htmlspecialchars($section); ?></td>
                            <td class="text-center"><?php echo htmlspecialchars($test_type); ?></td>
                            <td class="text-center"><?php echo htmlspecialchars($subject_name); ?></td>
                            <td class="text-center"><?php echo htmlspecialchars($subject_code); ?></td>
                            <td class="text-center"><?php echo htmlspecialchars($testmark); ?></td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Left Section: Marks and Count -->
            <div class="col-md-6">
                <h2>Enter Marks and Count</h2>
                <table class="table table-bordered" id="marksTable">
                    <thead>
                        <tr>
                            <th class="text-center">S.No</th>
                            <th class="text-center">Marks</th>
                            <th class="text-center">Count</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="text-center">1</td>
                            <td class="text-center"><input type="number" class="form-control marks" name="marks[]" required></td>
                            <td class="text-center"><input type="number" class="form-control count" name="count[]" required></td>
                        </tr>
                    </tbody>
                </table>
                <!-- Display Total Marks -->
                <div class="total-mark-container mb-3">
                    <label class="form-label fw-bold" for="total_mark">Total Mark:</label>
                    <input type="number" id="total_mark" class="form-control bg-transparent text-white border-light" readonly>
                </div>

                <!-- Add Row Button -->
                <button id="addRow" class="btn btn-secondary"><i class="bi bi-plus-circle me-2"></i>Add Row</button>

                <!-- Validate and Save -->
                <button id="validateBtn" class="btn btn-primary"> <i class="bi bi-check-circle me-2"></i>Save</button>
                <button class="btn btn-danger" onclick="backfunc()"><i class="bi bi-arrow-left-circle me-2"></i>Back</button>

                <div id="errorMsg" class="text-danger mt-2"></div>
                <div id="successMsg" class="text-success mt-2" style="display: none;"></div>
            </div>

            <!-- Right Section: COs Table -->
            <div class="col-md-6">
                <div id="questionSection" class="mt-5" style="display: none;">
                    <h2>Enter Course Outcomes for Each Question</h2>
                    <table class="table table-bordered table-hover align-middle" id="questionsTable">
                        <thead class="table-dark">
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
                successMsg.style.display = 'none';
            } else {
                errorMsg.innerText = '';
                successMsg.innerText = 'Marks are valid. Data saved successfully!';
                successMsg.style.display = 'block';

                // Generate COs table and show the question section
                generateQuestionsTable();
                document.getElementById('questionSection').style.display = 'block';
            }
        });

        document.getElementById('saveQuestions').addEventListener('click', function() {
            // Collect marks and counts from input fields
            const marksInputs = document.getElementsByClassName('marks');
            const countsInputs = document.getElementsByClassName('count');

            // Create arrays from input values
            const marksArray = Array.from(marksInputs).map(input => input.value);
            const countsArray = Array.from(countsInputs).map(input => input.value);

            // Convert to JSON for URL
            const marksJson = encodeURIComponent(JSON.stringify(marksArray));
            const countsJson = encodeURIComponent(JSON.stringify(countsArray));

            const questionTableBody = document.getElementById('questionsTable').getElementsByTagName('tbody')[0];
            const questions = questionTableBody.getElementsByTagName('tr');
            let formData = new FormData();

            // Add session data to formData
            formData.append('staffname', '<?php echo $staff_name; ?>'); 
            formData.append('regulation', '<?php echo $regulation; ?>'); 
            formData.append('year', '<?php echo $year; ?>');
            formData.append('semester', '<?php echo $semester; ?>');
            formData.append('department', '<?php echo $department; ?>');
            formData.append('section', '<?php echo $section; ?>');
            formData.append('test_type', '<?php echo $test_type; ?>');
            formData.append('testmark', '<?php echo $testmark; ?>');
            formData.append('subject_name', '<?php echo $subject_name; ?>');
            formData.append('subject_code', '<?php echo $subject_code; ?>');

            // Add COs to formData
            for (let i = 0; i < questions.length; i++) {
                const co = questions[i].querySelector('select').value;
                formData.append(`course_outcome[${i + 1}]`, co);
            }

            // Submit form data
            fetch('save_questions.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.text())
            .then(data => {
                if (data === 'Success') {
                    Swal.fire({
                        title: 'Success!',
                        text: 'Course Outcomes saved successfully!',
                        icon: 'success',
                        confirmButtonText: 'OK'
                    }).then(() => {
                        // Redirect with proper parameters
                        window.location.href = `test_enter.php?questionCount=${questions.length}&marks=${marksJson}&counts=${countsJson}`;
                    });
                } else {
                    Swal.fire({
                        title: 'Error!',
                        text: 'There was an error saving the data.',
                        icon: 'error',
                        confirmButtonText: 'OK'
                    });
                }
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire({
                    title: 'Error!',
                    text: 'An error occurred while submitting the form.',
                    icon: 'error',
                    confirmButtonText: 'OK'
                });
            });
        });

        function backfunc() {
            window.location.href = "mark_entry.php";
        }
    </script>
</body>

</html>