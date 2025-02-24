<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Staff Registration Form</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
</head>
<body>
    <?php
    // Enable error reporting for debugging
    error_reporting(E_ALL);
    ini_set('display_errors', 1);

    // Include the database connection file
    include('db.php');

    // Function to convert values to uppercase
    function toUpper($value) {
        if (is_string($value)) {
            return mb_strtoupper(trim($value), 'UTF-8');
        }
        return $value;
    }

    // Check if the form is submitted via POST
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        try {
            // Extract and convert form data to uppercase (except email fields)
            $first_name = toUpper($_POST['first_name'] ?? '');
            $middle_name = toUpper($_POST['middle_name'] ?? '');
            $last_name = toUpper($_POST['last_name'] ?? '');
            $date_of_birth = $_POST['date_of_birth'] ?? '';
            $gender = toUpper($_POST['gender'] ?? '');
            $blood_group = toUpper($_POST['blood_group'] ?? '');
            $caste = toUpper($_POST['caste'] ?? '');
            $religion = toUpper($_POST['religion'] ?? '');
            $phone_number = toUpper($_POST['phone_number'] ?? '');
            $alternate_phone_number = toUpper($_POST['alternate_phone_number'] ?? '');
            $email = strtolower(trim($_POST['email'] ?? '')); // Convert email to lowercase
            $alternate_email = strtolower(trim($_POST['alternate_email'] ?? '')); // Convert alternate email to lowercase
            $address = toUpper($_POST['address'] ?? '');
            $city = toUpper($_POST['city'] ?? '');
            $state = toUpper($_POST['state'] ?? '');
            $zip_code = toUpper($_POST['zip_code'] ?? '');
            $country = toUpper($_POST['country'] ?? '');
            $staff_id = toUpper($_POST['staff_id'] ?? '');
            $designation = toUpper($_POST['designation'] ?? '');
            $department = toUpper($_POST['department'] ?? '');
            $qualification = toUpper($_POST['qualification'] ?? '');
            $aadhaar_number = toUpper($_POST['aadhaar_number'] ?? '');
            $pan_number = toUpper($_POST['pan_number'] ?? '');
            $username = ($_POST['username'] ?? '');
            $password = password_hash($_POST['password'] ?? '', PASSWORD_BCRYPT); // Hash password

            // Validate required fields
            $requiredFields = [
                'first_name', 'last_name', 'date_of_birth', 'gender', 'phone_number', 'email', 'address',
                'city', 'state', 'zip_code', 'staff_id', 'designation', 'department', 'qualification', 'username', 'password'
            ];

            $missingFields = [];
            foreach ($requiredFields as $field) {
                if (empty($_POST[$field])) {
                    $missingFields[] = $field;
                }
            }

            if (!empty($missingFields)) {
                throw new Exception("The following fields are required: " . implode(', ', $missingFields));
            }

            // Handle profile image upload
            $profile_image = '';
            if (isset($_FILES['profile_image']) && $_FILES['profile_image']['error'] === UPLOAD_ERR_OK) {
                $file_name = $_FILES['profile_image']['name'];
                $file_tmp = $_FILES['profile_image']['tmp_name'];
                $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
                $allowed_extensions = ['jpg', 'jpeg', 'png', 'gif'];

                // Validate file type
                if (in_array($file_ext, $allowed_extensions)) {
                    // Ensure the uploads directory exists
                    if (!is_dir('uploads/staff')) {
                        mkdir('uploads/staff', 0777, true);
                    }

                    // Generate a unique file name to avoid duplicates
                    $profile_image = "uploads/staff/" . uniqid() . "." . $file_ext;
                    if (!move_uploaded_file($file_tmp, $profile_image)) {
                        throw new Exception("Failed to upload profile image.");
                    }
                } else {
                    throw new Exception("Invalid file type. Only JPG, JPEG, PNG, and GIF are allowed.");
                }
            }

            // Check for duplicate email or alternate email in the database
            $check_email_stmt = $mysqli->prepare("SELECT id FROM staff WHERE email = ? OR alternate_email = ?");
            $check_email_stmt->bind_param("ss", $email, $alternate_email);
            $check_email_stmt->execute();
            $check_email_stmt->store_result();

            if ($check_email_stmt->num_rows > 0) {
                throw new Exception("Email or alternate email already exists.");
            }
            $check_email_stmt->close();

            // Insert data into the database
            $stmt = $mysqli->prepare("
                INSERT INTO staff (
                    first_name, middle_name, last_name, date_of_birth, gender, blood_group, caste, religion,
                    phone_number, alternate_phone_number, email, alternate_email, address, city, state, zip_code, country,
                    profile_image, staff_id, designation, department, qualification, aadhaar_number, pan_number, username, password
                ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
            ");

            if (!$stmt) {
                throw new Exception("Failed to prepare the SQL statement: " . $mysqli->error);
            }

            $stmt->bind_param(
                "ssssssssssssssssssssssssss",
                $first_name,
                $middle_name,
                $last_name,
                $date_of_birth,
                $gender,
                $blood_group,
                $caste,
                $religion,
                $phone_number,
                $alternate_phone_number,
                $email,
                $alternate_email,
                $address,
                $city,
                $state,
                $zip_code,
                $country,
                $profile_image,
                $staff_id,
                $designation,
                $department,
                $qualification,
                $aadhaar_number,
                $pan_number,
                $username,
                $password
            );

            if (!$stmt->execute()) {
                throw new Exception("Database error: " . $stmt->error);
            }

            $stmt->close();

            // Success: Show SweetAlert2 message
            echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>";
            echo "<script>
                Swal.fire({
                    icon: 'success',
                    title: 'Registration Successful!',
                    text: 'Staff details have been saved successfully.',
                    showConfirmButton: false,
                    timer: 1500
                }).then(() => {
                    window.location.href = 'success_page.php'; // Redirect to success page
                });
            </script>";
        } catch (Exception $e) {
            // Handle errors
            echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>";
            echo "<script>
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: '" . addslashes($e->getMessage()) . "',
                });
            </script>";
        }
    }
    ?>
</body>
</html>