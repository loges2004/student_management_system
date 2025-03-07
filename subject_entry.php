<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Subject Entry Form</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <!-- SweetAlert2 for Alerts -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <!-- Custom CSS -->
    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .form-container {
            background: white;
            border-radius: 15px;
            padding: 2rem;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
            margin: 2rem auto;
            max-width: 800px;
        }

        .form-container h2 {
            color: #2a2a72;
            margin-bottom: 1.5rem;
            font-weight: 700;
        }

        .form-label {
            font-weight: 600;
            color: #333;
        }

        .form-control:focus {
            border-color: #2a2a72;
            box-shadow: 0 0 5px rgba(42, 42, 114, 0.5);
        }

        .btn-custom {
            background: linear-gradient(45deg, #2a2a72, #009ffd);
            color: white;
            border: none;
            padding: 10px 25px;
            border-radius: 25px;
            transition: all 0.3s ease;
        }

        .btn-custom:hover {
            transform: scale(1.05);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .animated {
            animation: fadeIn 0.6s ease-out;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="form-container animated">
            <h2>Subject Entry Form</h2>
            <form id="subjectForm" method="POST" action="save_subject.php">
                <div class="row g-3">
                    <!-- Subject Code -->
                    <div class="col-md-6">
                        <label for="subject_code" class="form-label">Subject Code</label>
                        <input type="text" class="form-control" id="subject_code" name="subject_code" required>
                    </div>
                    <!-- Subject Name -->
                    <div class="col-md-6">
                        <label for="subject_name" class="form-label">Subject Name</label>
                        <input type="text" class="form-control" id="subject_name" name="subject_name" required>
                    </div>
                    <!-- Department -->
                    <div class="col-md-6">
                        <label for="department" class="form-label">Department</label>
                        <select class="form-select" id="department" name="department" required>
                            <option value="">-- Select Department --</option>
                            <option value="Civil Engineering">Civil Engineering</option>
                            <option value="Mechanical Engineering">Mechanical Engineering</option>
                            <option value="Electronics and Communication Engineering">Electronics and Communication Engineering</option>
                            <option value="Electrical and Electronics Engineering">Electrical and Electronics Engineering</option>
                            <option value="Computer Science and Engineering">Computer Science and Engineering</option>
                            <option value="Information Technology">Information Technology</option>
                            <option value="Biomedical Engineering">Biomedical Engineering</option>
                            <option value="Computer Science and Business Systems">Computer Science and Business Systems</option>
                            <option value="Artificial Intelligence and Data Science">Artificial Intelligence and Data Science</option>
                            <option value="CSE Cyber Security">CSE Cyber Security</option>
                            <option value="CSE Artificial Intelligence and Machine Learning">CSE Artificial Intelligence and Machine Learning</option>
                            <option value="Electronics Engineering VLSI Design and Technology">Electronics Engineering VLSI Design and Technology</option>
                            <option value="Business Administration MBA">Business Administration MBA</option>
                            <option value="Computer Applications MCA">Computer Applications MCA</option>
                            <option value="Science and Humanities">Science and Humanities</option>
                        </select>
                    </div>
                    <?php
                    // Get the current year
                    $currentYear = date("Y");

                    // Define the range of years for the dropdown (e.g., current year to current year + 10)
                    $startYear = 2020;
                    $endYear = $currentYear + 10;

                    // Generate the dropdown options
                    echo '<label for="regulation">Regulation</label>';
                    echo '<select name="regulation" id="regulation" required>';
                    echo "<option value=''>---select the Regulation----</option>";
                    for ($year = $startYear; $year <= $endYear; $year++) {
                        echo "<option value='$year'>$year Regulation</option>";
                    }
                    echo '</select>';
                    ?>

                    <!-- Year -->
                    <div class="col-md-6">
                        <label for="year" class="form-label">Year</label>
                        <select class="form-select" id="year" name="year" required>
                            <option value="">-- Select Year --</option>
                            <option value="1">1st Year</option>
                            <option value="2">2nd Year</option>
                            <option value="3">3rd Year</option>
                            <option value="4">4th Year</option>
                        </select>
                    </div>
                    <!-- Semester (Dynamic) -->
                    <div class="col-md-6">
                        <label for="semester" class="form-label">Semester</label>
                        <select class="form-select" id="semester" name="semester" required>
                            <option value="">-- Select Semester --</option>
                        </select>
                    </div>
                    <!-- Type (Theory/Practical) -->
                    <div class="col-md-6">
                        <label for="type" class="form-label">Type</label>
                        <select class="form-select" id="type" name="type" required>
                            <option value="">-- Select Type --</option>
                            <option value="theory">Theory</option>
                            <option value="practical">Practical</option>
                        </select>
                    </div>
                    <!-- Sub-Type (Dynamic) -->
                    <div class="col-md-6">
                        <label for="sub_type" class="form-label">Sub-Type</label>
                        <select class="form-select" id="sub_type" name="sub_type" required>
                            <option value="">-- Select Sub-Type --</option>
                        </select>
                    </div>
                    <!-- Credit -->
                    <div class="col-md-6">
                        <label for="credit" class="form-label">Credit</label>
                        <input type="number" class="form-control" id="credit" name="credit" required>
                    </div>
                    <!-- Total Hours -->
                    <div class="col-md-6">
                        <label for="total_hours" class="form-label">Total Hours</label>
                        <input type="number" class="form-control" id="total_hours" name="total_hours" required>
                    </div>
                    <!-- Submit Button -->
                    <div class="col-12">
                        <button type="button" class="btn btn-danger me-5" onclick="window.location.href='subject_manage.php'">back</button>
                        <button type="submit" class="btn btn-custom">Submit</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- SweetAlert2 for Alerts -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- Dynamic Semester and Sub-Type Options -->
    <script>
        // Dynamic Semester Options
        document.getElementById('year').addEventListener('change', function() {
            const year = this.value;
            const semester = document.getElementById('semester');
            semester.innerHTML = '<option value="">-- Select Semester --</option>';

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

        // Dynamic Sub-Type Options
        document.getElementById('type').addEventListener('change', function() {
            const type = this.value;
            const subType = document.getElementById('sub_type');
            subType.innerHTML = '<option value="">-- Select Sub-Type --</option>';

            const options = {
                'theory': ['Elective', 'Open Elective', 'Professional', 'Mandatory'],
                'practical': ['Core', 'Integrated']
            };

            if (options[type]) {
                options[type].forEach((sub) => {
                    subType.innerHTML += `<option value="${sub.toLowerCase()}">${sub}</option>`;
                });
            }
        });
    </script>
</body>

</html>