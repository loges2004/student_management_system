<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Staff Registration Form</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <style>
        /* Include all CSS styles from the student form */
        :root {
            --primary-color: #2c3e50;
            --secondary-color: #3498db;
            --accent-color: #e74c3c;
        }

        body {
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            min-height: 100vh;
        }

        .form-label {
            position: relative;
        }

        .form-label::after {
            content: ' *';
            /* Append asterisk to the label */
            color: red;
            /* Set color to red */
            font-weight: bold;
            /* Optional: make the asterisk bold */
        }

        /* Remove asterisk for Middle Name field */
        #middlenameLabel::after {
            content: '';
            /* Remove the asterisk */
        }

        .container {
            padding: 2rem;
            animation: fadeInUp 0.5s ease-out;
        }

        .form-container {
            background: rgba(255, 255, 255, 0.95);
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            padding: 2rem;
            margin-top: 2rem;
        }

        .form-section {
            display: none;
            transform: translateX(50px);
            opacity: 0;
            transition: all 0.3s ease-in-out;
        }

        .form-section.active {
            display: block;
            transform: translateX(0);
            opacity: 1;
        }

        h2 {
            color: var(--primary-color);
            font-weight: 700;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.1);
            margin-bottom: 2rem !important;
        }

        .profile-preview {
            width: 150px;
            height: 150px;
            border: 4px solid var(--secondary-color);
            border-radius: 50%;
            overflow: hidden;
            margin: 0 auto 2rem;
            position: relative;
            transition: transform 0.3s ease;
        }

        .profile-preview:hover {
            transform: scale(1.05);
        }

        .profile-preview img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            color: white;
            font-size: smaller;
        }

        .upload-label {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            background: rgba(52, 152, 219, 0.8);
            color: white;
            padding: 0.5rem;
            text-align: center;
            cursor: pointer;
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .profile-preview:hover .upload-label {
            opacity: 1;
        }

        .form-label {
            font-weight: 600;
            color: var(--primary-color);
            margin-bottom: 0.5rem;
        }

        .btn-custom {
            background: var(--secondary-color);
            color: white;
            padding: 0.75rem 2rem;
            border-radius: 30px;
            transition: all 0.3s ease;
            border: none;
        }

        .btn-custom:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(52, 152, 219, 0.4);
        }

        .btn-secondary {
            background: #95a5a6;
            color: white;
            border-radius: 30px;
            padding: 0.75rem 2rem;
        }

        .btn-primary {
            background: rgb(58, 113, 243);
            color: white;
            border-radius: 30px;
            padding: 0.75rem 2rem;
        }

        .progress-bar {
            height: 8px;
            background: #ecf0f1;
            border-radius: 10px;
            margin: 2rem 0;
            overflow: hidden;
        }

        .progress {
            height: 100%;
            background: var(--secondary-color);
            transition: width 0.3s ease;
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

        .input-group {
            transition: transform 0.3s ease;
        }

        .input-group:focus-within {
            transform: scale(1.02);
        }

        .form-control {
            border: 2px solid #bdc3c7;
            border-radius: 10px;
            padding: 0.75rem 1.25rem;
            transition: border-color 0.3s ease;
        }

        .form-control:focus {
            border-color: var(--secondary-color);
            box-shadow: none;
        }

        .section-title {
            color: var(--primary-color);
            border-left: 5px solid var(--secondary-color);
            padding-left: 1rem;
            margin-bottom: 2rem;
            font-weight: 700;
        }

        .header-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: linear-gradient(90deg, #007bff, #6f42c1);
            padding: 5px 15px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            margin-bottom: 30px;
        }

        .header-title {
            color: white;
            font-size: 28px;
            font-weight: bold;
            text-align: center;
            flex-grow: 1;
        }


        .profile-preview img {
            border-radius: 50%;
            object-fit: cover;
            font-size: small;
            border: 3px solid white;
            box-shadow: 0px 3px 6px rgba(0, 0, 0, 0.2);
            transition: transform 0.3s ease-in-out;
        }


        .profile-preview img:hover {
            transform: scale(1.1) rotate(5deg);
            box-shadow: 0px 6px 12px rgba(0, 0, 0, 0.3);
        }

        .input-group {
            display: flex;
            align-items: center;
        }

        .input-group .btn {
            margin-left: -1px;
            /* Adjust button to be aligned next to input */
        }
    </style>
</head>

<body>
<div class="container">
        <div class="form-container">
            <div class="header-container">
                <h2 class="header-title">Staff Registration Form</h2>
                <div class="profile-preview">
                    <img id="profilePreview" src="./images/avatar-profile-icon-flat-style-male-user-profile-vector-illustration-isolated-background-man-profile-sign-business-concept_157943-38764.avif" alt="Profile Preview">
                </div>
            </div>
            <form id="registrationForm" action="save_staff.php" method="POST" enctype="multipart/form-data" class="needs-validation" novalidate>
                <!-- Section 1: Personal Information -->
                <div class="form-section active">
                    <h4>Personal Information</h4>
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label for="first_name" class="form-label">First Name</label>
                            <input type="text" class="form-control" id="first_name" name="first_name" required>
                            <div class="invalid-feedback">Please enter first name.</div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label id="middlenameLabel" for="middle_name" class="form-label">Middle Name</label>
                            <input type="text" class="form-control" id="middle_name" name="middle_name">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="last_name" class="form-label">Last Name</label>
                            <input type="text" class="form-control" id="last_name" name="last_name" required>
                            <div class="invalid-feedback">Please enter last name.</div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="dob" class="form-label">Date of Birth</label>
                            <input type="date" class="form-control" id="dob" name="date_of_birth" required>
                            <div class="invalid-feedback">Please enter date of birth.</div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="gender" class="form-label">Gender</label>
                            <select class="form-control" id="gender" name="gender" required>
                                <option value="">-- Select Gender --</option>
                                <option value="MALE">Male</option>
                                <option value="FEMALE">Female</option>
                                <option value="OTHER">Other</option>
                            </select>
                            <div class="invalid-feedback">Please select gender.</div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="blood_group" class="form-label">Blood Group</label>
                            <select class="form-control" id="blood_group" name="blood_group" required>
                                <option value="">-- Select Blood Group --</option>
                                <option value="A+">A+</option>
                                <option value="A-">A-</option>
                                <option value="B+">B+</option>
                                <option value="B-">B-</option>
                                <option value="O+">O+</option>
                                <option value="O-">O-</option>
                                <option value="AB+">AB+</option>
                                <option value="AB-">AB-</option>
                            </select>
                            <div class="invalid-feedback">Please select blood group.</div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="profile_image" class="form-label text-center">Profile Image</label>
                            <input name="profile_image" class="form-control" type="file" id="profileImage" accept="image/*" required>
                            <div class="invalid-feedback">Please upload a profile image.</div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="caste" class="form-label">Caste</label>
                            <input type="text" class="form-control" id="caste" name="caste" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="religion" class="form-label">Religion</label>
                            <input type="text" class="form-control" id="religion" name="religion" required>
                        </div>
                    </div>
                    <div class="d-flex justify-content-end">
                        <button type="button" class="btn btn-primary next-section">Next</button>
                    </div>
                </div>

                <!-- Section 2: Contact Information -->
                <div class="form-section">
                    <h4>Contact Information</h4>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="phone_number" class="form-label">Phone Number</label>
                            <input type="text" class="form-control" id="phone_number" name="phone_number" required>
                            <div class="invalid-feedback">Please enter phone number.</div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" name="email" required>
                            <div class="invalid-feedback">Please enter valid email.</div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="alternate_phone_number" class="form-label">Alternate Phone Number</label>
                            <input type="text" class="form-control" id="alternate_phone_number" name="alternate_phone_number">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="alternate_email" class="form-label">Alternate Email</label>
                            <input type="email" class="form-control" id="alternate_email" name="alternate_email">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <label for="address" class="form-label">Address</label>
                            <textarea class="form-control" id="address" name="address" required></textarea>
                            <div class="invalid-feedback">Please enter address.</div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-3 mb-3">
                            <label for="city" class="form-label">City</label>
                            <input type="text" class="form-control" id="city" name="city" required>
                            <div class="invalid-feedback">Please enter city.</div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label for="state" class="form-label">State</label>
                            <input type="text" class="form-control" id="state" name="state" required>
                            <div class="invalid-feedback">Please enter state.</div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label for="zip_code" class="form-label">Zip Code</label>
                            <input type="text" class="form-control" id="zip_code" name="zip_code" required>
                            <div class="invalid-feedback">Please enter zip code.</div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label for="country" class="form-label">Country</label>
                            <input type="text" class="form-control" id="country" name="country" required>
                        </div>
                    </div>
                    <div class="d-flex justify-content-start">
                        <button type="button" class="btn btn-secondary prev-section">Previous</button>
                    </div>
                    <div class="d-flex justify-content-end">
                        <button type="button" class="btn btn-primary next-section">Next</button>
                    </div>
                </div>

                <!-- Section 3: Employment Details -->
                <div class="form-section">
                    <h4>Employment Details</h4>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="staff_id" class="form-label">Staff ID</label>
                            <input type="text" class="form-control" id="staff_id" name="staff_id" required>
                            <div class="invalid-feedback">Please enter staff ID.</div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="designation" class="form-label">Designation</label>
                            <select class="form-control" id="designation" name="designation" required>
                                <option value="">-- Select Designation --</option>
                                <option value="PROFESSOR">Professor</option>
                                <option value="ASSOCIATE_PROFESSOR">Associate Professor</option>
                                <option value="ASSISTANT">Assistant</option>
                                <option value="LAB_TECHNICIAN">Lab Technician</option>
                                <option value="ATTENDER">Attender</option>
                                <option value="ADMIN">Admin</option>
                                <option value="HOD">HOD</option>
                            </select>
                            <div class="invalid-feedback">Please select designation.</div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="department" class="form-label">Department</label>
                            <select class="form-control" id="department" name="department" required>
                                <option value="">-- Select Department --</option>
                                <option value="civil">Civil Engineering</option>
                                <option value="mechanical">Mechanical Engineering</option>
                                <option value="ece">Electronics and Communication Engineering</option>
                                <option value="eee">Electrical and Electronics Engineering</option>
                                <option value="cse">Computer Science and Engineering</option>
                                <option value="it">Information Technology</option>
                                <option value="bme">Biomedical Engineering</option>
                                <option value="csbs">Computer Science and Business Systems</option>
                                <option value="ai_ds">Artificial Intelligence and Data Science</option>
                                <option value="cse_cyber">CSE (Cyber Security)</option>
                                <option value="cse_ai_ml">CSE (Artificial Intelligence and Machine Learning)</option>
                                <option value="vlsi">Electronics Engineering (VLSI Design and Technology)</option>
                                <option value="mba">Business Administration (MBA)</option>
                                <option value="mca">Computer Applications (MCA)</option>
                                <option value="sci_hum">Science and Humanities</option>
                            </select>
                            <div class="invalid-feedback">Please select department.</div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="qualification" class="form-label">Qualification</label>
                            <input type="text" class="form-control" id="qualification" name="qualification" required>
                            <div class="invalid-feedback">Please enter qualification.</div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="aadhaar_number" class="form-label">Aadhaar Number</label>
                            <input type="text" class="form-control" id="aadhaar_number" name="aadhaar_number">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="pan_number" class="form-label">PAN Number</label>
                            <input type="text" class="form-control" id="pan_number" name="pan_number">
                        </div>
                    </div>
                    <div class="d-flex justify-content-start">
                        <button type="button" class="btn btn-secondary prev-section">Previous</button>
                    </div>
                    <div class="d-flex justify-content-end">
                        <button type="button" class="btn btn-primary next-section">Next</button>
                    </div>
                </div>

                <!-- Section 4: Login Credentials -->
                <div class="form-section">
                    <h4>Login Credentials</h4>
                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-person-fill"></i></span>
                                <div class="form-floating flex-grow-1">
                                    <input type="text" class="form-control" id="username" name="username" placeholder="Username" required>
                                    <label for="username">Username</label>
                                    <div class="invalid-feedback">Please enter a username.</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12 mb-3">
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-lock-fill"></i></span>
                                <div class="form-floating flex-grow-1">
                                    <input type="password" id="password" name="password" class="form-control"
                                        pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}"
                                        title="Password must contain at least one number, one uppercase letter, one lowercase letter, and be at least 8 characters long."
                                        placeholder="Enter your password" required>
                                    <label for="password">Password</label>
                                </div>
                                <button class="btn btn-outline-secondary" type="button" id="showPassword" onclick="togglePasswordVisibility()">
                                    <i class="bi bi-eye-slash-fill"></i>
                                </button>
                            </div>
                            <div class="invalid-feedback">
                                Password must contain at least one number, one uppercase letter, one lowercase letter, and be at least 8 characters long.
                            </div>
                        </div>
                        <div class="col-md-12 mb-3">
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-lock-fill"></i></span>
                                <div class="form-floating flex-grow-1">
                                    <input type="password" id="confirm_password" name="confirm_password" class="form-control" placeholder="Confirm your password" required>
                                    <label for="confirm_password">Confirm Password</label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="d-flex justify-content-start">
                        <button type="button" class="btn btn-secondary prev-section">Previous</button>
                    </div>
                    <div class="d-flex justify-content-end">
                        <button type="submit" class="btn btn-success">Submit</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Bootstrap JS and SweetAlert2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        function togglePasswordVisibility() {
            const password = document.getElementById('password'); // Define password inside the function
            const showPasswordBtn = document.getElementById('showPassword');
            if (password.type === 'password') {
                password.type = 'text';
                showPasswordBtn.innerHTML = '<i class="bi bi-eye-fill"></i>'; // Switch to eye-open icon
            } else {
                password.type = 'password';
                showPasswordBtn.innerHTML = '<i class="bi bi-eye-slash-fill"></i>'; // Switch to eye-closed icon
            }
        }
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('registrationForm');
            const password = document.getElementById('password');
            const confirm_password = document.getElementById('confirm_password');
            const usernameInput = document.getElementById('username'); // Ensure usernameInput is defined



            // Validate password and confirm password match
            if (confirm_password) {
                confirm_password.addEventListener('input', function() {
                    if (password.value !== confirm_password.value) {
                        confirm_password.setCustomValidity("Passwords do not match.");
                        confirm_password.classList.add('is-invalid');
                    } else {
                        confirm_password.setCustomValidity("");
                        confirm_password.classList.remove('is-invalid');
                    }
                });
            }

            // Validate input fields
            function validateInput(input) {
                if (input.checkValidity()) {
                    input.classList.remove('is-invalid');
                    input.classList.add('is-valid'); // Add valid class
                } else {
                    input.classList.remove('is-valid');
                    input.classList.add('is-invalid'); // Add invalid class
                }
            }

            // Event listener for username validation
            if (usernameInput) {
                usernameInput.addEventListener('input', function() {
                    validateInput(usernameInput);
                });
            }

            // Event listener for password validation
            if (password) {
                password.addEventListener('input', function() {
                    validateInput(password);
                });
            }

            // Prevent form submission if invalid
            if (form) {
                form.addEventListener('submit', function(event) {
                    event.preventDefault();

                    const isUsernameValid = usernameInput ? usernameInput.checkValidity() : true;
                    const isPasswordValid = password ? password.checkValidity() : true;
                    const isConfirmPasswordValid = confirm_password ? confirm_password.checkValidity() : true;

                    if (isUsernameValid && isPasswordValid && isConfirmPasswordValid) {
                        // Submit the form if everything is valid
                        Swal.fire({
                            icon: 'success',
                            title: 'Form Submitted Successfully!',
                            showConfirmButton: true,
                        }).then(() => {
                            form.submit(); // Only submit if validation passes
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Please fill in all the required fields correctly.',
                            showConfirmButton: true,
                        });
                    }
                });
            }

            // Validate all inputs in the current section
            // Modify the validateSection function
            function validateSection(section) {
                const inputs = section.querySelectorAll('input, select, textarea');
                let isSectionValid = true;

                // Handle conditional education fields
                const eduType = document.getElementById('education_type');
                if (eduType && eduType.value) {
                    const visibleFields = eduType.value === '12th_study' ?
                        twelfthDetails.querySelectorAll('[required]') :
                        diplomaDetails.querySelectorAll('[required]');

                    visibleFields.forEach(input => {
                        validateInput(input);
                        if (!input.checkValidity()) isSectionValid = false;
                    });
                }

                // Validate other visible fields
                inputs.forEach(input => {
                    if (window.getComputedStyle(input.closest('.row')).display !== 'none') {
                        validateInput(input);
                        if (!input.checkValidity()) isSectionValid = false;
                    }
                });

                return isSectionValid;
            }

            // Prevent form submission on Enter key press
            if (form) {
                form.addEventListener('keydown', function(event) {
                    if (event.key === 'Enter') {
                        event.preventDefault();
                        const currentSection = document.querySelector('.form-section.active');
                        const isSectionValid = validateSection(currentSection);

                        if (!isSectionValid) {
                            const firstInvalidInput = currentSection.querySelector('.is-invalid');
                            if (firstInvalidInput) {
                                firstInvalidInput.focus();
                            }
                            Swal.fire({
                                icon: 'error',
                                title: 'Missing Information',
                                text: 'Please fill out all required fields.',
                                confirmButtonText: 'OK'
                            });
                        } else {
                            const nextButton = currentSection.querySelector('.next-section');
                            if (nextButton) {
                                nextButton.click();
                            } else {
                                form.submit();
                            }
                        }
                    }
                });
            }

            // Add input event listeners to validate fields as the user types
            if (form) {
                form.querySelectorAll('input, select, textarea').forEach(input => {
                    input.addEventListener('input', function() {
                        validateInput(input);
                    });
                });
            }

            // Section navigation
            const formSections = document.querySelectorAll('.form-section');
            let currentSectionIndex = 0;

            document.querySelectorAll('.next-section').forEach(button => {
                button.addEventListener('click', () => {
                    const currentSection = formSections[currentSectionIndex];
                    const isSectionValid = validateSection(currentSection);

                    if (isSectionValid && currentSectionIndex < formSections.length - 1) {
                        currentSection.classList.remove('active');
                        currentSectionIndex++;
                        formSections[currentSectionIndex].classList.add('active');
                    } else {
                        currentSection.classList.add('was-validated');
                    }
                });
            });

            document.querySelectorAll('.prev-section').forEach(button => {
                button.addEventListener('click', () => {
                    if (currentSectionIndex > 0) {
                        formSections[currentSectionIndex].classList.remove('active');
                        currentSectionIndex--;
                        formSections[currentSectionIndex].classList.add('active');
                    }
                });
            });
            // Conditional Fields Handling
            const educationType = document.getElementById('education_type');
            const twelfthDetails = document.getElementById('12th_details');
            const diplomaDetails = document.getElementById('diploma_details');
            const transferSelect = document.getElementById('transferred_student');
            const transferReasonField = document.getElementById('transfer_reason_field');

            // Handle the display of 12th and Diploma details based on the selection
            // Update the education type event handler
            if (educationType && twelfthDetails && diplomaDetails) {
                educationType.addEventListener('change', function() {
                    // Toggle required attributes
                    const is12th = this.value === '12th_study';

                    twelfthDetails.querySelectorAll('input, select').forEach(field => {
                        field.required = is12th;
                        field.disabled = !is12th;
                    });

                    diplomaDetails.querySelectorAll('input, select').forEach(field => {
                        field.required = !is12th;
                        field.disabled = is12th;
                    });

                    // Update visibility
                    twelfthDetails.style.display = is12th ? 'flex' : 'none';
                    diplomaDetails.style.display = is12th ? 'none' : 'flex';
                });
            }

            // Set initial required states
            const eduType = document.getElementById('education_type');
            if (eduType) {
                const is12th = eduType.value === '12th_study';
                twelfthDetails.querySelectorAll('input, select').forEach(field => field.required = is12th);
                diplomaDetails.querySelectorAll('input, select').forEach(field => field.required = !is12th);
            }

            // Handle the display of transfer reason based on selection
            if (transferSelect && transferReasonField) {
                transferSelect.addEventListener('change', function() {
                    if (this.value === 'YES') {
                        transferReasonField.style.display = 'block';
                    } else {
                        transferReasonField.style.display = 'none';
                    }
                });
            }
            // Profile image preview
            function previewImage(event) {
                const reader = new FileReader();
                const preview = document.getElementById('profilePreview');
                if (preview) {
                    reader.onload = function() {
                        preview.src = reader.result;
                        preview.style.display = 'block';
                    };
                    reader.readAsDataURL(event.target.files[0]);
                }
            }

            const profileImageInput = document.getElementById('profileImage');
            if (profileImageInput) {
                profileImageInput.addEventListener('change', previewImage);
            }
        });
    </script>



</body>

</html>