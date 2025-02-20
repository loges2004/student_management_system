<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Registration Form</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- SweetAlert2 CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <!-- Custom CSS -->
    <style>
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
                <h2 class="header-title">Student Registration Form</h2>
                <div class="profile-preview">
                    <img id="profilePreview" src="./images/avatar-profile-icon-flat-style-male-user-profile-vector-illustration-isolated-background-man-profile-sign-business-concept_157943-38764.avif" alt="Profile Preview">

                </div>
            </div>
            <form id="registrationForm" method="POST" enctype="multipart/form-data" class="needs-validation" novalidate>
                <!-- Section 1: Personal Details -->
                <div class="form-section active">
                    <h4>Personal Details</h4>
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label for="first_name" class="form-label">First Name</label>
                            <input type="text" class="form-control" id="first_name" name="first_name" required>
                            <div class="invalid-feedback">Please enter first name.</div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="middle_name" id="middlenameLabel" class="form-label">Middle Name</label>
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
                            <label for="register_no" class="form-label">Register Number</label>
                            <input type="text" class="form-control" id="register_no" name="register_no" required>
                            <div class="invalid-feedback">Please enter register number.</div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="roll_no" class="form-label">Roll Number</label>
                            <input type="text" class="form-control" id="roll_no" name="roll_no" required>
                            <div class="invalid-feedback">Please enter roll number.</div>
                        </div>
                    </div>
                    <div class="row">
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
                        <div class="col-md-6 mb-3">
                            <label for="dob" class="form-label">Date of Birth</label>
                            <input type="date" class="form-control" id="dob" name="dob" required>
                            <div class="invalid-feedback">Please enter date of birth.</div>
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
                            <label for="address" class="form-label">Address</label>
                            <textarea class="form-control" id="address" name="address" required></textarea>
                            <div class="invalid-feedback">Please enter address.</div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label for="city" class="form-label">City</label>
                            <input type="text" class="form-control" id="city" name="city" required>
                            <div class="invalid-feedback">Please enter city.</div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="state" class="form-label">State</label>
                            <input type="text" class="form-control" id="state" name="state" required>
                            <div class="invalid-feedback">Please enter state.</div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="zip_code" class="form-label">Zip Code</label>
                            <input type="text" class="form-control" id="zip_code" name="zip_code" required>
                            <div class="invalid-feedback">Please enter zip code.</div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="country" class="form-label">Country</label>
                            <input type="text" class="form-control" id="country" name="country" required>
                            <div class="invalid-feedback">Please enter country.</div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="profile_image" class="form-label text-center">Profile Image</label>
                            <input name="profile_image" class="form-control" type="file" id="profileImage" accept="image/" required>
                            <div class="invalid-feedback">Please upload a profile image.</div>
                        </div>
                    </div>
                    <div class="d-flex justify-content-end">
                        <button type="button" class="btn btn-primary next-section">Next</button>
                    </div>

                </div>

                <!-- Section 2: Contact and Academic Details -->
                <div class="form-section">
                    <h4>Contact and Academic Details</h4>
                    <div class="row">
                        <div class="col-md-5 mb-3">
                            <label for="personal_email" class="form-label">Personal Email</label>
                            <input type="email" class="form-control" id="personal_email" name="personal_email" required>
                            <div class="invalid-feedback">Please enter personal email.</div>
                        </div>
                        <div class="col-md-5 mb-3">
                            <label for="college_email" class="form-label">College Email</label>
                            <input type="email" class="form-control" id="college_email" name="college_email"
                                pattern="^[a-zA-Z0-9._%+-]+@psnacet\.edu\.in$"
                                title="Enter a valid college email (e.g., example@psnacet.edu.in)" required>
                            <div class="invalid-feedback">Please enter a valid college email.</div>
                        </div>

                        <div class="col-md-2 mb-3">
                            <label for="phone_no" class="form-label">Phone Number</label>
                            <input type="text" class="form-control" id="phone_no" name="phone_no" required>
                            <div class="invalid-feedback">Please enter phone number.</div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label for="admission_type" class="form-label">Admission Type</label>
                            <select class="form-control" id="admission_type" name="admission_type" required>
                                <option value="">-- Select Admission Type --</option>
                                <option value="government_quota">Government Quota</option>
                                <option value="management_quota">Management Quota</option>
                                <option value="7.5_reservation_quota">7.5 Reservation Quota</option>
                            </select>
                            <div class="invalid-feedback">Please select admission type.</div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="lateral_entry" class="form-label">Lateral Entry</label>
                            <select class="form-control" id="lateral_entry" name="lateral_entry" required>
                                <option value="">-- Select --</option>
                                <option value="yes">Yes</option>
                                <option value="no">No</option>
                            </select>
                            <div class="invalid-feedback">Please select lateral entry status.</div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="first_graduate" class="form-label">First Graduate</label>
                            <select class="form-control" id="first_graduate" name="first_graduate" required>
                                <option value="">-- Select --</option>
                                <option value="YES">Yes</option>
                                <option value="NO">No</option>
                            </select>
                            <div class="invalid-feedback">Please select first graduate status.</div>
                        </div>


                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="day_scholar_hosteller" class="form-label">Day Scholar/Hosteller</label>
                            <select class="form-control" id="day_scholar_hosteller" name="day_scholar_hosteller" required>
                                <option value="">-- Select --</option>
                                <option value="DAY_SCHOLAR">Day Scholar</option>
                                <option value="HOSTELLER">Hosteller</option>
                            </select>
                            <div class="invalid-feedback">Please select day scholar/hosteller.</div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="year" class="form-label">Year</label>
                            <select class="form-control" id="year" name="year" required>
                                <option value="">-- Select Year --</option>
                                <option value="1">1st Year</option>
                                <option value="2">2nd Year</option>
                                <option value="3">3rd Year</option>
                                <option value="4">4th Year</option>
                            </select>
                            <div class="invalid-feedback">Please select year.</div>
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
                            <label for="section" class="form-label">Section</label>
                            <input type="text" class="form-control" id="section" name="section" required>
                            <div class="invalid-feedback">Please enter section.</div>
                        </div>
                    </div>
                    <div class="d-flex justify-content-start">
                        <button type="button" class="btn btn-secondary prev-section">Previous</button>

                    </div>


                    <div class="d-flex justify-content-end">
                        <button type="button" class="btn btn-primary next-section">Next</button>
                    </div>
                </div>

                <!-- Section 3: Family and Academic Details -->
                <div class="form-section">
                    <h4>Family and Academic Details</h4>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="father_name" class="form-label">Father's Name</label>
                            <input type="text" class="form-control" id="father_name" name="father_name" required>
                            <div class="invalid-feedback">Please enter father's name.</div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="father_occupation" class="form-label">Father's Occupation</label>
                            <input type="text" class="form-control" id="father_occupation" name="father_occupation" required>
                            <div class="invalid-feedback">Please enter father's occupation.</div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-5 mb-3">
                            <label for="mother_name" class="form-label">Mother's Name</label>
                            <input type="text" class="form-control" id="mother_name" name="mother_name" required>
                            <div class="invalid-feedback">Please enter mother's name.</div>
                        </div>
                        <div class="col-md-5 mb-3">
                            <label for="mother_occupation" class="form-label">Mother's Occupation</label>
                            <input type="text" class="form-control" id="mother_occupation" name="mother_occupation" required>
                            <div class="invalid-feedback">Please enter mother's occupation.</div>
                        </div>
                        <div class="col-md-2 mb-3">
                            <label for="parent_number" class="form-label">Parent's Contact Number</label>
                            <input type="text" class="form-control" id="parent_number" name="parent_number" required>
                            <div class="invalid-feedback">Please enter parent's contact number.</div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label for="tenth_school_name" class="form-label">10th School Name</label>
                            <input type="text" class="form-control" id="tenth_school_name" name="tenth_school_name" required>
                            <div class="invalid-feedback">Please enter 10th school name.</div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label for="10th_medium" class="form-label">10th Grade Medium of Instruction</label>
                            <select class="form-control" id="10th_medium" name="10th_medium" required>
                                <option value="">-- Select Medium for 10th --</option>
                                <option value="english">English Medium</option>
                                <option value="tamil">Tamil Medium</option>
                            </select>
                            <div class="invalid-feedback">Please select medium for 10th grade.</div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label for="tenth_passed_year" class="form-label">10th Passed Year</label>
                            <input type="number" class="form-control" id="tenth_passed_year" name="tenth_passed_year" required>
                            <div class="invalid-feedback">Please enter 10th passed year.</div>
                        </div>
                        <div class="col-md-2 mb-3">
                            <label for="tenth_percentage" class="form-label">10th Percentage</label>
                            <input type="number" class="form-control" id="tenth_percentage" name="tenth_percentage" required>
                            <div class="invalid-feedback">Please enter 10th percentage.</div>
                        </div>
                    </div>
                    <!-- Education Type Selection -->
                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <label for="education_type" class="form-label">12th Study or Diploma</label>
                            <select class="form-control" id="education_type" name="education_type" required>
                                <option value="">-- Select --</option>
                                <option value="12th_study">12th Study</option>
                                <option value="diploma_study">Diploma</option>
                            </select>
                            <div class="invalid-feedback">Please select education type.</div>
                        </div>
                    </div>

                    <!-- 12th Details (Conditional) -->
                    <div class="row" id="12th_details" style="display: none;">
                        <!-- 12th School Name -->
                        <div class="col-md-3 mb-3">
                            <label for="twelfth_school_name" class="form-label">12th School Name</label>
                            <input type="text" class="form-control" id="twelfth_school_name" name="twelfth_school_name">
                        </div>

                        <!-- 12th Medium of Instruction -->
                        <div class="col-md-3 mb-3">
                            <label for="12th_medium" class="form-label">12th Grade Medium of Instruction</label>
                            <select class="form-control" id="12th_medium" name="12th_medium" required>
                                <option value="">-- Select Medium for 12th --</option>
                                <option value="english">English Medium</option>
                                <option value="tamil">Tamil Medium</option>
                            </select>
                            <div class="invalid-feedback">Please select medium for 12th grade.</div>
                        </div>

                        <!-- 12th Passed Year -->
                        <div class="col-md-2 mb-3">
                            <label for="twelfth_passed_year" class="form-label">12th Passed Year</label>
                            <input type="number" class="form-control" id="twelfth_passed_year" name="twelfth_passed_year">
                        </div>

                        <!-- 12th Percentage -->
                        <div class="col-md-2 mb-3">
                            <label for="twelfth_percentage" class="form-label">12th Percentage</label>
                            <input type="number" class="form-control" id="twelfth_percentage" name="twelfth_percentage" required>
                            <div class="invalid-feedback">Please enter 12th percentage.</div>
                        </div>

                        <!-- 12th Cutoff Mark -->
                        <div class="col-md-2 mb-3">
                            <label for="twelfth_cutoff" class="form-label">12th Cutoff Mark</label>
                            <input type="number" step="0.01" class="form-control" id="twelfth_cutoff" name="twelfth_cutoff">
                        </div>
                    </div>
                    <!-- Diploma Details (Conditional) -->
                    <div class="row" id="diploma_details" style="display: none;">
                        <div class="col-md-4 mb-3">
                            <label for="diploma_school_name" class="form-label">Diploma School Name</label>
                            <input type="text" class="form-control" id="diploma_school_name" name="diploma_school_name">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="diploma_passed_year" class="form-label">Diploma Passed Year</label>
                            <input type="number" class="form-control" id="diploma_passed_year" name="diploma_passed_year">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="diploma_percentage" class="form-label">Diploma Percentage</label>
                            <input type="number" step="0.01" class="form-control" id="diploma_percentage" name="diploma_percentage" min="0" max="100">
                        </div>
                    </div>

                    <!-- Transferred Student Section -->
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="transferred_student" class="form-label">Are you a Transferred Student?</label>
                            <select class="form-control" id="transferred_student" name="transferred_student" required>
                                <option value="">-- Select --</option>
                                <option value="YES">Yes</option>
                                <option value="NO">No</option>
                            </select>
                            <div class="invalid-feedback">Please select transferred student status.</div>
                        </div>
                        <div class="col-md-6 mb-3" id="transfer_reason_field" style="display: none;">
                            <label for="transfer_reason" class="form-label">Transfer Reason</label>
                            <input type="text" class="form-control" id="transfer_reason" name="transfer_reason">
                            <div class="invalid-feedback">Please enter transfer reason.</div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label for="emis_no" class="form-label">EMIS Number</label>
                            <input type="text" class="form-control" id="emis_no" name="emis_no" required>
                            <div class="invalid-feedback">Please enter EMIS number.</div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="aadhaar_no" class="form-label">Aadhaar Number</label>
                            <input type="text" class="form-control" id="aadhaar_no" name="aadhaar_no" required>
                            <div class="invalid-feedback">Please enter Aadhaar number.</div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="pan_no" class="form-label">PAN Number</label>
                            <input type="text" class="form-control" id="pan_no" name="pan_no" required>
                            <div class="invalid-feedback">Please enter PAN number.</div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="caste" class="form-label">Caste</label>
                            <input type="text" class="form-control" id="caste" name="caste" required>
                            <div class="invalid-feedback">Please enter caste.</div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="religion" class="form-label">Religion</label>
                            <input type="text" class="form-control" id="religion" name="religion" required>
                            <div class="invalid-feedback">Please enter religion.</div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="nationality" class="form-label">Nationality</label>
                            <input type="text" class="form-control" id="nationality" name="nationality" required>
                            <div class="invalid-feedback">Please enter nationality.</div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="mother_tongue" class="form-label">Mother Tongue</label>
                            <input type="text" class="form-control" id="mother_tongue" name="mother_tongue" required>
                            <div class="invalid-feedback">Please enter mother tongue.</div>
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
                        <!-- Username Field -->
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

                        <!-- Password Field -->
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

                        <!-- Confirm Password Field -->
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
        </div>

    </div>

    </form>
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
    if(eduType && eduType.value) {
        const visibleFields = eduType.value === '12th_study' ? 
            twelfthDetails.querySelectorAll('[required]') : 
            diplomaDetails.querySelectorAll('[required]');
            
        visibleFields.forEach(input => {
            validateInput(input);
            if(!input.checkValidity()) isSectionValid = false;
        });
    }

    // Validate other visible fields
    inputs.forEach(input => {
        if(window.getComputedStyle(input.closest('.row')).display !== 'none') {
            validateInput(input);
            if(!input.checkValidity()) isSectionValid = false;
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
if(eduType) {
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
    <?php
    error_reporting(E_ALL); // Report all PHP errors
    ini_set('display_errors', 1); // Display errors to the browser

    // Database connection
    include('db.php');

    // Function to validate Aadhaar number
    function validateAadhaar($aadhaar)
    {
        return preg_match('/^\d{12}$/', $aadhaar);
    }

    // Function to validate phone number
    function validatePhone($phone)
    {
        return preg_match('/^\d{10}$/', $phone);
    }

    // Function to validate EMIS number
    function validateEMIS($emis)
    {
        return preg_match('/^\d{10,16}$/', $emis);
    }

    // Function to convert input to uppercase
    function toUpper($data)
    {
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

        // Extract form data
        $first_name = toUpper($_POST['first_name']);
        $middle_name = toUpper($_POST['middle_name']);
        $last_name = toUpper($_POST['last_name']);
        $roll_no = toUpper($_POST['roll_no']);
        $gender = toUpper($_POST['gender']);
        $year = $_POST['year'];
        $department = toUpper($_POST['department']);
        $section = toUpper($_POST['section']);
        $dob = $_POST['dob'];
        $blood_group = toUpper($_POST['blood_group']);
        $address = toUpper($_POST['address']);
        $city = toUpper($_POST['city']);
        $state = toUpper($_POST['state']);
        $zip_code = toUpper($_POST['zip_code']);
        $country = toUpper($_POST['country']);
        $personal_email = strtolower(trim($_POST['personal_email']));
        $college_email = strtolower(trim($_POST['college_email']));
        $phone_no = $_POST['phone_no'];
        $admission_type = toUpper($_POST['admission_type']);
        $lateral_entry = $_POST['lateral_entry'];
        $first_graduate = $_POST['first_graduate'];
        $day_scholar_hosteller = toUpper($_POST['day_scholar_hosteller']);
        $father_name = toUpper($_POST['father_name']);
        $father_occupation = toUpper($_POST['father_occupation']);
        $mother_name = toUpper($_POST['mother_name']);
        $mother_occupation = toUpper($_POST['mother_occupation']);
        $parent_number = $_POST['parent_number'];
        $tenth_school_name = toUpper($_POST['tenth_school_name']);
        $tenth_medium = $_POST['10th_medium'];
        $tenth_passed_year = $_POST['tenth_passed_year'];
        $tenth_percentage = $_POST['tenth_percentage'];
        $twelfth_percentage = $_POST['twelfth_percentage'];
        $education_type = $_POST['education_type'];
        $twelfth_school_name = ($education_type === '12th_study') ? toUpper($_POST['twelfth_school_name']) : null;
        $twelfth_medium = ($education_type === '12th_study') ? $_POST['12th_medium'] : null;
        $twelfth_passed_year = ($education_type === '12th_study') ? $_POST['twelfth_passed_year'] : null;
        $twelfth_cutoff = ($education_type === '12th_study') ? $_POST['twelfth_cutoff'] : null;
        $diploma_school_name = ($education_type === 'diploma_study') ? toUpper($_POST['diploma_school_name']) : null;
        $diploma_passed_year = ($education_type === 'diploma_study') ? $_POST['diploma_passed_year'] : null;
        $diploma_percentage = ($education_type === 'diploma_study') ? $_POST['diploma_percentage'] : null;
        $emis_no = $_POST['emis_no'];
        $aadhaar_no = $_POST['aadhaar_no'];
        $pan_no = toUpper($_POST['pan_no']);
        $caste = toUpper($_POST['caste']);
        $religion = toUpper($_POST['religion']);
        $nationality = toUpper($_POST['nationality']);
        $mother_tongue = toUpper($_POST['mother_tongue']);
        $username = $_POST['username'];
        $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
        $transferred_student = $_POST['transferred_student'];
        $transfer_reason = ($transferred_student === 'YES') ? toUpper($_POST['transfer_reason']) : null;

        // Handle profile image upload
        $profile_image = null;
        if ($_FILES['profile_image']['error'] == UPLOAD_ERR_OK) {
            $target_dir = "uploads/";
            $target_file = $target_dir . basename($_FILES['profile_image']['name']);
            if (move_uploaded_file($_FILES['profile_image']['tmp_name'], $target_file)) {
                $profile_image = $target_file;
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
        }

        // Validate Aadhaar, Phone, and EMIS
        if (!validateAadhaar($aadhaar_no)) {
            echo "<script>
            Swal.fire({
                icon: 'error',
                title: 'Invalid Aadhaar',
                text: 'Invalid Aadhaar number. It must be 12 digits.',
                confirmButtonText: 'OK'
            });
          </script>";
            exit();
        }

        if (!validatePhone($phone_no)) {
            echo "<script>
            Swal.fire({
                icon: 'error',
                title: 'Invalid Phone Number',
                text: 'Invalid phone number. It must be 10 digits.',
                confirmButtonText: 'OK'
            });
          </script>";
            exit();
        }

        if (!validateEMIS($emis_no)) {
            echo "<script>
            Swal.fire({
                icon: 'error',
                title: 'Invalid EMIS Number',
                text: 'Invalid EMIS number. It must be 10-16 digits.',
                confirmButtonText: 'OK'
            });
          </script>";
            exit();
        }

        // Insert data into the database
        $stmt = $mysqli->prepare("
        INSERT INTO stud (
            first_name, middle_name, last_name, register_no, roll_no, gender, years, department, section, dob, blood_group, address, city, state, zip_code, country, personal_email, college_email, phone_no, admission_type, lateral_entry, first_graduate, day_scholar_hosteller, father_name, father_occupation, mother_name, mother_occupation, parent_number, tenth_school_name, tenth_medium, tenth_passed_year, tenth_percentage, education_type, twelfth_school_name, twelfth_medium, twelfth_passed_year,twelfth_percentage, twelfth_cutoff, diploma_school_name, diploma_passed_year, diploma_percentage, emis_no, aadhaar_no, pan_no, caste, religion, nationality, mother_tongue, username, password, profile_image, transferred_student, transfer_reason
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?,?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
    ");

        if (!$stmt) {
            die("Prepare failed: " . $mysqli->error);
        }

        // Bind parameters
        $stmt->bind_param(
            "sssssssssssssssssssssssssssssssssssssssssssssssssssss",
            $first_name,
            $middle_name,
            $last_name,
            $register_no,
            $roll_no,
            $gender,
            $year,
            $department,
            $section,
            $dob,
            $blood_group,
            $address,
            $city,
            $state,
            $zip_code,
            $country,
            $personal_email,
            $college_email,
            $phone_no,
            $admission_type,
            $lateral_entry,
            $first_graduate,
            $day_scholar_hosteller,
            $father_name,
            $father_occupation,
            $mother_name,
            $mother_occupation,
            $parent_number,
            $tenth_school_name,
            $tenth_medium,
            $tenth_passed_year,
            $tenth_percentage,
            $education_type,
            $twelfth_school_name,
            $twelfth_medium,
            $twelfth_passed_year,
            $twelfth_percentage,
            $twelfth_cutoff,
            $diploma_school_name,
            $diploma_passed_year,
            $diploma_percentage,
            $emis_no,
            $aadhaar_no,
            $pan_no,
            $caste,
            $religion,
            $nationality,
            $mother_tongue,
            $username,
            $password,
            $profile_image,
            $transferred_student,
            $transfer_reason
        );

        if ($stmt->execute()) {
            // Registration successful
            echo "<script>
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