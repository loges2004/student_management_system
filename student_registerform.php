
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Registration Form</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- SweetAlert2 CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body>

    <div class="container mt-5">
        <h2 class="text-center mb-4">Student Registration Form</h2>
        <form method="POST" action="" enctype="multipart/form-data">
            <!-- Student Details -->
            <div class="mb-3">
                <label for="student_name" class="form-label">Student Name</label>
                <input type="text" class="form-control" id="student_name" name="student_name" required>
            </div>
            <div class="mb-3">
                <label for="register_no" class="form-label">Register Number</label>
                <input type="text" class="form-control" id="register_no" name="register_no" required>
            </div>
            <div class="mb-3">
                <label for="roll_no" class="form-label">Roll Number</label>
                <input type="text" class="form-control" id="roll_no" name="roll_no" required>
            </div>
            <div class="mb-3">
                <label for="gender" class="form-label">Gender</label>
                <select class="form-control" id="gender" name="gender" required>
                    <option value="MALE">Male</option>
                    <option value="FEMALE">Female</option>
                    <option value="OTHER">Other</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="year" class="form-label">Year</label>
                <select class="form-select" id="year" name="year" required>
                    <option value="">-- Select Year --</option>
                    <option value="1">1st Year</option>
                    <option value="2">2nd Year</option>
                    <option value="3">3rd Year</option>
                    <option value="4">4th Year</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="department" class="form-label">Department</label>
                <input type="text" class="form-control" id="department" name="department" required>
            </div>
            <div class="mb-3">
                <label for="section" class="form-label">Section</label>
                <input type="text" class="form-control" id="section" name="section" required>
            </div>
            <div class="mb-3">
                <label for="dob" class="form-label">Date of Birth</label>
                <input type="date" class="form-control" id="dob" name="dob" required>
            </div>
            <div class="mb-3">
                <label for="blood_group" class="form-label">Blood Group</label>
                <input type="text" class="form-control" id="blood_group" name="blood_group" required>
            </div>
            <div class="mb-3">
                <label for="address" class="form-label">Address</label>
                <textarea class="form-control" id="address" name="address" required></textarea>
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control" id="email" name="email" required>
            </div>
            <div class="mb-3">
                <label for="phone_no" class="form-label">Phone Number</label>
                <input type="text" class="form-control" id="phone_no" name="phone_no" required>
            </div>
            <div class="mb-3">
                <label for="admission_type" class="form-label">Admission Type</label>
                <select class="form-control" id="admission_type" name="admission_type" required>
                    <option value="" disabled selected>Select Admission Type</option>
                    <option value="government_quota">Government Quota</option>
                    <option value="management_quota">Management Quota</option>
                    <option value="7.4_reservation_quota">7.4% Reservation Quota</option>
                </select>
            </div>

            <div class="mb-3">
                <label for="first_graduate" class="form-label">First Graduate</label>
                <select class="form-control" id="first_graduate" name="first_graduate" required>
                    <option value="" disabled selected>Select an option</option>
                    <option value="YES">Yes</option>
                    <option value="NO">No</option>
                </select>

            </div>
            <div class="mb-3">
                <label for="day_scholar_hosteller" class="form-label">Day Scholar/Hosteller</label>
                <select class="form-control" id="day_scholar_hosteller" name="day_scholar_hosteller" required>
                    <option value="" disabled selected>Select an option</option>
                    <option value="DAY_SCHOLAR">Day Scholar</option>
                    <option value="HOSTELLER">Hosteller</option>
                </select>

            </div>
            <!-- Parent Details -->
            <div class="mb-3">
                <label for="father_name" class="form-label">Father's Name</label>
                <input type="text" class="form-control" id="father_name" name="father_name" required>
            </div>
            <div class="mb-3">
                <label for="father_occupation" class="form-label">Father's Occupation</label>
                <input type="text" class="form-control" id="father_occupation" name="father_occupation" required>
            </div>
            <div class="mb-3">
                <label for="mother_name" class="form-label">Mother's Name</label>
                <input type="text" class="form-control" id="mother_name" name="mother_name" required>
            </div>
            <div class="mb-3">
                <label for="mother_occupation" class="form-label">Mother's Occupation</label>
                <input type="text" class="form-control" id="mother_occupation" name="mother_occupation" required>
            </div>
            <div class="mb-3">
                <label for="parent_number" class="form-label">Parent's Phone Number</label>
                <input type="text" class="form-control" id="parent_number" name="parent_number" required>
            </div>
            <!-- Academic Details -->
            <div class="mb-3">
                <label for="passed_school_name" class="form-label">Passed School Name</label>
                <input type="text" class="form-control" id="passed_school_name" name="passed_school_name" required>
            </div>
            <div class="mb-3">
                <label for="tenth_passed_year" class="form-label">10th Passed Year</label>
                <input type="number" class="form-control" id="tenth_passed_year" name="tenth_passed_year" required>
            </div>
            <div class="mb-3">
                <label for="tenth_percentage" class="form-label">10th Percentage</label>
                <input type="number" step="0.01" class="form-control" id="tenth_percentage" name="tenth_percentage" required>
            </div>
            <div class="mb-3">
                <label for="twelfth_passed_year" class="form-label">12th Passed Year</label>
                <input type="number" class="form-control" id="twelfth_passed_year" name="twelfth_passed_year" required>
            </div>
            <div class="mb-3">
                <label for="twelfth_percentage" class="form-label">12th Percentage</label>
                <input type="number" step="0.01" class="form-control" id="twelfth_percentage" name="twelfth_percentage" required>
            </div>
            <!-- Other Details -->
            <div class="mb-3">
                <label for="aadhaar_no" class="form-label">Aadhaar Number</label>
                <input type="text" class="form-control" id="aadhaar_no" name="aadhaar_no" required>
            </div>
            <div class="mb-3">
                <label for="pan_no" class="form-label">PAN Number</label>
                <input type="text" class="form-control" id="pan_no" name="pan_no" required>
            </div>
            <div class="mb-3">
                <label for="caste" class="form-label">Caste</label>
                <input type="text" class="form-control" id="caste" name="caste" required>
            </div>
            <div class="mb-3">
                <label for="religion" class="form-label">Religion</label>
                <input type="text" class="form-control" id="religion" name="religion" required>
            </div>
            <div class="mb-3">
                <label for="nationality" class="form-label">Nationality</label>
                <input type="text" class="form-control" id="nationality" name="nationality" required>
            </div>
            <div class="mb-3">
                <label for="mother_tongue" class="form-label">Mother Tongue</label>
                <input type="text" class="form-control" id="mother_tongue" name="mother_tongue" required>
            </div>
            <div class="mb-3">
                <label for="emis_no" class="form-label">EMIS Number</label>
                <input type="text" class="form-control" id="emis_no" name="emis_no" required>
            </div>
            <!-- Login Details -->
            <div class="mb-3">
                <label for="username" class="form-label">Username</label>
                <input type="text" class="form-control" id="username" name="username" required>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" class="form-control" id="password" name="password" required>
            </div>
            <!-- Profile Image -->
            <div class="mb-3">
                <label for="profile_image" class="form-label">Profile Image</label>
                <input type="file" class="form-control" id="profile_image" name="profile_image" required>
            </div>
            <button type="submit" class="btn btn-primary">Submit</button>
        </form>
        <!-- SweetAlert2 JS -->
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    </div>
    <?php
error_reporting(E_ALL); // Report all PHP errors
ini_set('display_errors', 1); // Display errors to the browser

// Database connection
include('db.php');

// Function to validate Aadhaar number
function validateAadhaar($aadhaar) {
    return preg_match('/^\d{12}$/', $aadhaar);
}

// Function to validate phone number
function validatePhone($phone) {
    return preg_match('/^\d{10}$/', $phone);
}

// Function to validate EMIS number
function validateEMIS($emis) {
    return preg_match('/^\d{10,16}$/', $emis);
}

// Function to convert input to uppercase
function toUpper($data) {
    return strtoupper(trim($data));
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Validate and sanitize inputs
    $register_no = toUpper($_POST['register_no']);

    // Check if the student is already registered
    $check_query = $mysqli->prepare("SELECT register_no FROM stud WHERE register_no = ?");
    $check_query->bind_param("s", $register_no);
    $check_query->execute();
    $check_query->store_result();

    if ($check_query->num_rows > 0) {
        // Student is already registered
        echo "<script>
                Swal.fire({
                    icon: 'warning',
                    title: 'Already Registered',
                    text: 'This student is already registered. Please log in.',
                    confirmButtonText: 'OK'
                }).then(() => {
                    window.location.href = 'login.php'; // Redirect to login page
                });
              </script>";
        exit();
    }

    // If not registered, proceed with registration
    $student_name = toUpper($_POST['student_name']);
    $roll_no = toUpper($_POST['roll_no']);
    $gender = toUpper($_POST['gender']);
    $year = $_POST['year'];
    $department = toUpper($_POST['department']);
    $section = toUpper($_POST['section']);
    $dob = $_POST['dob'];
    $blood_group = toUpper($_POST['blood_group']);
    $address = toUpper($_POST['address']);
    $email = strtolower(trim($_POST['email']));
    $phone_no = $_POST['phone_no'];
    $admission_type = toUpper($_POST['admission_type']);
    $first_graduate = $_POST['first_graduate'];
    $day_scholar_hosteller = toUpper($_POST['day_scholar_hosteller']);
    $father_name = toUpper($_POST['father_name']);
    $father_occupation = toUpper($_POST['father_occupation']);
    $mother_name = toUpper($_POST['mother_name']);
    $mother_occupation = toUpper($_POST['mother_occupation']);
    $parent_number = $_POST['parent_number'];
    $passed_school_name = toUpper($_POST['passed_school_name']);
    $tenth_passed_year = $_POST['tenth_passed_year'];
    $tenth_percentage = $_POST['tenth_percentage'];
    $twelfth_passed_year = $_POST['twelfth_passed_year'];
    $twelfth_percentage = $_POST['twelfth_percentage'];
    $aadhaar_no = $_POST['aadhaar_no'];
    $pan_no = toUpper($_POST['pan_no']);
    $caste = toUpper($_POST['caste']);
    $religion = toUpper($_POST['religion']);
    $nationality = toUpper($_POST['nationality']);
    $mother_tongue = toUpper($_POST['mother_tongue']);
    $emis_no = $_POST['emis_no'];
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);

    // Validate Aadhaar, Phone, and EMIS
    if (!validateAadhaar($aadhaar_no)) {
        die("Invalid Aadhaar number. It must be 12 digits.");
    }
    if (!validatePhone($phone_no)) {
        die("Invalid phone number. It must be 10 digits.");
    }
    if (!validateEMIS($emis_no)) {
        die("Invalid EMIS number. It must be 10-16 digits.");
    }

    // Handle image upload
    if ($_FILES['profile_image']['error'] == UPLOAD_ERR_OK) {
        $target_dir = "uploads/";
        $target_file = $target_dir . basename($_FILES['profile_image']['name']);
        if (move_uploaded_file($_FILES['profile_image']['tmp_name'], $target_file)) {
            // File uploaded successfully
        } else {
            echo "<script>
                    Swal.fire({
                        icon: 'error',
                        title: 'File Upload Error',
                        text: 'Failed to upload profile image.',
                        confirmButtonText: 'OK'
                    });
                  </script>";
            exit();
        }
    } else {
        echo "<script>
                Swal.fire({
                    icon: 'error',
                    title: 'File Upload Error',
                    text: 'Error uploading profile image.',
                    confirmButtonText: 'OK'
                });
              </script>";
        exit();
    }

    // Insert into database
    $stmt = $mysqli->prepare("
        INSERT INTO stud (
            student_name, register_no, roll_no, gender, years, department, section, dob, blood_group, address,
            email, phone_no, admission_type, first_graduate, day_scholar_hosteller, father_name, father_occupation,
            mother_name, mother_occupation, parent_number, passed_school_name, tenth_passed_year, tenth_percentage, twelfth_passed_year,
            twelfth_percentage, aadhaar_no, pan_no, caste, religion, nationality, mother_tongue, emis_no, username, password, profile_image
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
    ");

    $stmt->bind_param(
        "ssssissssssssssssssssssssssssssssss",
        $student_name,
        $register_no,
        $roll_no,
        $gender,
        $year,
        $department,
        $section,
        $dob,
        $blood_group,
        $address,
        $email,
        $phone_no,
        $admission_type,
        $first_graduate,
        $day_scholar_hosteller,
        $father_name,
        $father_occupation,
        $mother_name,
        $mother_occupation,
        $parent_number,
        $passed_school_name,
        $tenth_passed_year,
        $tenth_percentage,
        $twelfth_passed_year,
        $twelfth_percentage,
        $aadhaar_no,
        $pan_no,
        $caste,
        $religion,
        $nationality,
        $mother_tongue,
        $emis_no,
        $username,
        $password,
        $target_file
    );

    if ($stmt->execute()) {
        // Registration successful
        echo "<script>
                console.log('SweetAlert script executed');
                Swal.fire({
                    icon: 'success',
                    title: 'Registration Successful',
                    text: 'You have been successfully registered!',
                    confirmButtonText: 'OK'
                }).then(() => {
                    window.location.href = 'login.php'; // Redirect to login page
                });
              </script>";
        exit();
    } else {
        echo "<script>
                console.log('Error occurred');
                Swal.fire({
                    icon: 'error',
                    title: 'Database Error',
                    text: 'An error occurred: " . addslashes($stmt->error) . "',
                    confirmButtonText: 'OK'
                });
              </script>";
        exit();
    }
}
?>
</body>

</html>