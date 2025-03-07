<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Assessment Details</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary-color: #4a90e2;
            --secondary-color: #6c757d;
            --hover-color: #357abd;
            --background-gradient: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
        }

        body {
            font-family: 'Poppins', sans-serif;
            background: var(--background-gradient);
            min-height: 100vh;
        }

        .form-card {
            background: rgba(255, 255, 255, 0.95);
            border-radius: 15px;
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.12);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .form-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 12px 40px rgba(0, 0, 0, 0.15);
        }

        .form-control {
            border: 2px solid #e9ecef;
            border-radius: 8px;
            transition: all 0.3s ease;
        }

        .form-control:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(74, 144, 226, 0.25);
        }

        .form-label {
            font-weight: 500;
            color: var(--secondary-color);
        }

        .btn-primary {
            background-color: var(--primary-color);
            border: none;
            padding: 12px 30px;
            border-radius: 8px;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .btn-primary:hover {
            background-color: var(--hover-color);
            transform: translateY(-2px);
        }

        .section-divider {
            border-top: 2px dashed #dee2e6;
            margin: 2rem 0;
        }

        @media (max-width: 768px) {
            .form-card {
                margin: 1rem;
            }
        }

        .animated-heading {
            animation: fadeInUp 0.8s ease;
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .form-select {
            cursor: pointer;
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16'%3e%3cpath fill='none' stroke='%236c757d' stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M2 5l6 6 6-6'/%3e%3c/svg%3e");
            background-size: 16px 12px;
        }

        .input-group {
            position: relative;
            margin-bottom: 1.5rem;
        }

        .input-group label {
            display: block;
            margin-bottom: 0.5rem;
        }
    </style>
</head>

<body>
    <div class="container py-5">
        <h1 class="text-center mb-5 animated-heading test-center">📝 Assessment Details Entry</h1>

        <div class="form-card p-4 p-lg-5 mx-auto" style="max-width: 800px;">
            <form action="validate_subject.php" method="POST">
                <div class="row g-4">
                    <!-- Staff Details -->
                    <div class="col-12">
                        <h5 class="text-primary text-center mb-3">👤 Staff Information</h5>
                    </div>
                    <div class="col-md-6">
                        <label for="staff_id" class="form-label">Staff ID</label>
                        <input type="text" class="form-control" id="staff_id" name="staff_id" required>
                    </div>
                    <div class="col-md-6">
                        <label for="staff_name" class="form-label">Staff Name</label>
                        <input type="text" class="form-control" id="staff_name" name="staff_name" required>
                    </div>

                    <div class="col-12">
                        <div class="section-divider"></div>
                    </div>

                    <!-- Academic Details -->
                    <div class="col-12">
                        <h5 class="text-primary text-center mb-3">🎓 Academic Information</h5>
                    </div>
                    <div class="col-md-4">
                        <label for="year" class="form-label">Academic Year</label>
                        <select class="form-select" id="year" name="year" required>
                            <option value="">-- Select Year --</option>
                            <option value="1">1st Year</option>
                            <option value="2">2nd Year</option>
                            <option value="3">3rd Year</option>
                            <option value="4">4th Year</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label for="semester" class="form-label">Semester</label>
                        <select class="form-select" id="semester" name="semester" required>
                            <!-- Options populated by JS -->
                        </select>
                    </div>
                    <div class="col-md-4">
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
                    <div class="col-md-6">
                        <label for="section" class="form-label">Section</label>
                        <select class="form-select" id="section" name="section" required>
                            <option value="">-- Select Section --</option>
                            <option value="A">Section A</option>
                            <option value="B">Section B</option>
                            <option value="C">Section C</option>
                            <option value="D">Section D</option>
                        </select>
                    </div>
                    <!-- Regulation Dropdown -->
    <div class="col-md-6 mb-3">
        <?php
        // Get the current year
        $currentYear = date("Y");
        // Define the range of years for the dropdown (e.g., current year to current year + 10)
        $startYear = 2020;
        $endYear = $currentYear + 10;
        // Generate the dropdown options for Regulation
        echo '<label for="regulation" class="form-label">Regulation</label>';
        echo '<select class="form-control" name="regulation" id="regulation" required>';
        echo "<option value=''>---select the Regulation----</option>";
        for ($year = $startYear; $year <= $endYear; $year++) {
            echo "<option value='$year'>$year Regulation</option>";
        }
        echo '</select>';
        ?>
        <div class="invalid-feedback">Please select regulation.</div>
    </div>

                    <div class="col-12">
                        <div class="section-divider"></div>
                    </div>

                    <!-- Subject Details -->
                    <div class="col-12">
                        <h5 class="text-primary text-center mb-3">📚 Subject Information</h5>
                    </div>
                    <div class="col-md-8">
                        <label for="subject_name" class="form-label">Subject Name</label>
                        <input type="text" class="form-control" id="subject_name" name="subject_name" required>
                    </div>
                    <div class="col-md-4">
                        <label for="subject_code" class="form-label">Subject Code</label>
                        <input type="text" class="form-control" id="subject_code" name="subject_code" required>
                    </div>

                    <div class="col-12">
                        <div class="section-divider"></div>
                    </div>

                    <!-- Assessment Details -->
                    <div class="col-12">
                        <h5 class="text-primary text-center mb-3">📊 Assessment Details</h5>
                    </div>
                    <div class="col-md-4">
                        <label for="test_type" class="form-label">Test Type</label>
                        <select class="form-select" id="test_type" name="test_type" required>
                            <option value="">-- Select Type --</option>
                            <option value="serialtest1">Serial Test 1</option>
                            <option value="serialtest2">Serial Test 2</option>
                            <option value="Assignment1">Assignment 1</option>
                            <option value="Assignment2">Assignment 2</option>
                            <option value="Quiz1">Quiz 1</option>
                            <option value="Quiz2">Quiz 2</option>
                            <option value="Seminar1">Seminar1</option>
                            <option value="Seminar2">Seminar2</option>
                            
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label for="testmark" class="form-label">Total Marks</label>
                        <input type="number" class="form-control" id="testmark" name="testmark" required>
                    </div>
                    <div class="col-md-4">
                        <label for="passmark" class="form-label">Passing Marks</label>
                        <input type="number" class="form-control" id="passmark" name="passmark" required>
                    </div>

                    <!-- Submit Button -->
                    <div class="col-12 text-center mt-5">
                        <button type="submit" name="next" class="btn btn-primary px-5 py-2">
                            💾 Save Details
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <script>
        // JavaScript for dynamic semester options
        document.getElementById('year').addEventListener('change', function() {
            const year = this.value;
            const semester = document.getElementById('semester');
            semester.innerHTML = '';

            const options = {
                '1': ['Semester 1', 'Semester 2'],
                '2': ['Semester 3', 'Semester 4'],
                '3': ['Semester 5', 'Semester 6'],
                '4': ['Semester 7', 'Semester 8']
            };

            if (options[year]) {
                options[year].forEach((sem, index) => {
                    semester.innerHTML += `<option value="${index + 1}">${sem}</option>`;
                });
            }
        });
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>