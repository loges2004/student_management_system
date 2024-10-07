<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration Form</title>
    <link rel="stylesheet" href="style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
</head>

<body>
    <div class="center">
        <div class="form-container">
            <form action="registration.php" method="post" onsubmit="return validateForm()">
                <h1 class="text-center">Signup</h1>
                <?php
                $image = "images/psnalogo.jpg";
                ?>
                <img src="<?php echo $image ?>" alt="psna" width="60px" height="60px">
                <hr>
                <div class="row">
                    <div class="col-md-6">
                        <div class="input-group mb-3">
                            <span class="input-group-text"><i class="bi bi-person"></i></span>
                            <div class="form-floating">
                                <input type="text" id="firstname" name="firstname" class="form-control" placeholder="Enter your firstname" required>
                                <label for="firstname">First Name</label>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="input-group mb-3">
                            <span class="input-group-text"><i class="bi bi-person"></i></span>
                            <div class="form-floating">
                                <input type="text" id="lastname" name="lastname" class="form-control" placeholder="Enter your lastname" required>
                                <label for="lastname">Last Name</label>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="input-group mb-3">
                    <span class="input-group-text"><i class="bi bi-check2-circle"></i></span>
                    <div class="form-floating">
                        <select class="form-select" id="userType" name="selectusertype" required>
                            <option value="">--Select Role--</option>
                            <option value="staff">👨‍💼 Staff</option>
                            <option value="student">🎓 Student</option>
                        </select>
                        <label for="userType">Select User Type</label>
                    </div>
                </div>
                <div class="input-group mb-3">
                    <span class="input-group-text"><i class="bi bi-envelope-fill"></i></span>
                    <div class="form-floating">
                        <input type="email" id="email" name="email" class="form-control" placeholder="Enter your email" required>
                        <label for="email">Email</label>
                    </div>
                </div>
                <div class="input-group mb-3">
                    <span class="input-group-text"><i class="bi bi-lock"></i></span>
                    <div class="form-floating">
                        <input type="password" id="password" name="password" class="form-control" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}" title="Must contain at least one number and one uppercase and lowercase letter, and at least 8 or more characters" placeholder="Enter your password" required>
                        <label for="password">Password</label>
                        <button class="btn btn-outline-secondary" type="button" id="showPassword" onclick="togglePasswordVisibility()">
                            <i class="bi bi-eye-fill"></i>
                        </button>
                    </div>
                </div>
                <div class="input-group mb-3">
                    <span class="input-group-text"><i class="bi bi-lock-fill"></i></span>
                    <div class="form-floating">
                        <input type="password" id="confirm_password" name="confirm_password" class="form-control" placeholder="Confirm your password" required>
                        <label for="confirm_password">Confirm Password</label>
                    </div>
                </div>

                <input type="reset" class="btn btn-secondary" value="Clear">
                <input type="button" class="btn btn-danger" value="Cancel" onclick="cancelAction()"><br><br>
                <input type="submit" name="submit" class="btn btn-primary" value="Create Account">
                <br><br>
                <center><b>Already have an account?</b><a href="login22.php">Login</a></center>
            </form>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        function validateForm() {
            const email = document.getElementById("email").value;
            const password = document.getElementById("password").value;
            const confirm_password = document.getElementById("confirm_password").value;
            const collegeDomain = "@psnacet.edu.in";

            // Validate college email domain
            if (!email.endsWith(collegeDomain)) {
                Swal.fire({
                    icon: 'error',
                    title: 'Invalid email domain',
                    text: 'Please use your college email (@psnacet.edu.in)',
                    position: "top",
                    showConfirmButton: true,
                });
                return false;
            }

            // Validate password match
            if (password !== confirm_password) {
                Swal.fire({
                    icon: 'error',
                    title: 'Password and Confirm Password do not match.',
                    position: "top",
                    showConfirmButton: true,
                });
                return false;
            }
        }

        function togglePasswordVisibility() {
            const passwordInput = document.getElementById("password");
            const showPasswordBtn = document.getElementById("showPassword");
            if (passwordInput.type === "password") {
                passwordInput.type = "text";
                showPasswordBtn.innerHTML = '<i class="bi bi-eye-fill"></i>';
            } else {
                passwordInput.type = "password";
                showPasswordBtn.innerHTML = '<i class="bi bi-eye-slash-fill"></i>';
            }
        }

        function cancelAction() {
            window.location.href = "homepage.php";
        }

        document.getElementById("email").addEventListener("input", function(event) {
            if (this.checkValidity()) {
                this.classList.remove("is-invalid");
                this.classList.add("is-valid");
            } else {
                this.classList.remove("is-valid");
                this.classList.add("is-invalid");
            }
        });

        document.getElementById("password").addEventListener("input", function(event) {
            if (this.checkValidity()) {
                this.classList.remove("is-invalid");
                this.classList.add("is-valid");
            } else {
                this.classList.remove("is-valid");
                this.classList.add("is-invalid");
            }
        });
    </script>

    <?php
$hostname = "localhost";
$username = "root";
$password = "";
$databasename = "lk";
$port = 4306;
$mysqli= mysqli_connect($hostname,$username,$password,$databasename,$port);
if(!$mysqli){
    echo ('connect error: '.mysqli_connect_error());
}

    if (isset($_POST['submit'])) {
        $firstname = $_POST['firstname'];
        $lastname = $_POST['lastname'];
        $selectusertype = $_POST['selectusertype'];
        $email = $_POST['email'];
        $password = $_POST['password'];

        // Server-side validation for college email domain
        $collegeDomain = "@psnacet.edu.in";
        if (substr($email, -strlen($collegeDomain)) !== $collegeDomain) {
            echo <<<EOD
            <script>
                Swal.fire({
                    position: 'top',
                    icon: 'error',
                    title: 'Invalid email domain',
                    text: 'Please use your college email (@psnacet.edu.in)',
                    showConfirmButton: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = "register.php"; // Redirect to registration page
                    }
                });
            </script>
            EOD;
            exit;
        }

        // Hash the password
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Insert user into the database
        $result = mysqli_query($mysqli, "INSERT INTO students (firstname, lastname, selectusertype, email, password) VALUES ('$firstname','$lastname','$selectusertype', '$email', '$hashed_password')");

        if ($result) {
            echo <<<EOD
            <script>
                Swal.fire({
                    position: 'top',
                    icon: 'success',
                    title: 'User registered successfully',
                    showConfirmButton: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = "login2.php"; // Redirect to login page
                    }
                });
            </script>
            EOD;
        } else {
            echo <<<EOD
            <script>
                Swal.fire({
                    position: 'top',
                    icon: 'error',
                    title: 'Registration failed',
                    text: 'Please try again later',
                    showConfirmButton: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = "register.php"; // Redirect to registration page
                    }
                });
            </script>
            EOD;
        }
    }
    ?>
</body>

</html>
