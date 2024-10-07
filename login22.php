<!DOCTYPE html>
<html>

<head>
    <title>Login Page</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>

    <style>
   .center {
    background-color: white;
    margin: 140px auto;
    height: auto;
    width: 80%; /* Adjust width for responsiveness */
    max-width: 340px; /* Limit maximum width for larger screens */
    padding: 25px;
    border-radius: 15px;
}

body{
    background-image: url(images/psna.jpg);
    background-size: cover;
}



.input-group {
    position: relative;
}

#showPassword {
    position: absolute;
    top: 50%;
    right: 10px; /* Adjust as needed */
    transform: translateY(-50%);
    z-index: 1;
    border: none;
    background-color: transparent;
}
#showPassword:hover{
    background-color: grey;
}
img{
 display:inline-block;
 margin: auto;
 
}

h1{
   display:inline-block;
   margin-right: 10px;
}
    </style>
</head>

<body>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" rel="stylesheet">

<div class="center d-flex justify-content-center align-items-center">
    <form action="login_process.php" method="post" class="w-100">
<h1>Login</h1>
<?php
$image="images/psnalogo.jpg";
?>

<img src="<?php echo $image?>" alt="psna" width="50px" height="50px">

<hr>
    <div class="input-group mb-3 mt-3">
    <span class="input-group-text"><i class="bi bi-envelope-fill"></i></span>
    <div class="form-floating">
        <input type="email" id="email" name="email" class="form-control" placeholder="enter the email" required>
        <label for="email">Email</label>
    </div>
</div>
<div class="input-group mb-3 mt-3">
    <span class="input-group-text"><i class="bi bi-lock-fill"></i></span>
    <div class="form-floating">
        <input type="password" id="password" name="password" class="form-control" placeholder="enter the password" required>
        <label for="password">Password</label>
   
        <button class="btn btn-outline-secondary" type="button" id="showPassword" onclick="togglePasswordVisibility()">
        <i class="bi bi-eye-fill"></i>
    </button>
    </div>
 </div>

        <div class="mb-3">
            <a href="forgot_password.php">Forgot password?</a>
        </div>
        <div class="mb-3">
            <input class="btn btn-primary w-100" type="submit" value="Login">
        </div>
        <div class="text-center mb-3">
            <span><strong> Don't have an account?</strong></span>
            <a href="register.php">Signup</a>
        </div>
    </form>
</div>


    <script>
     function togglePasswordVisibility() {
    var passwordInput = document.getElementById("password");
    var showPasswordBtn = document.getElementById("showPassword");
    if (passwordInput.type === "password") {
        passwordInput.type = "text";
        showPasswordBtn.innerHTML = '<i class="bi bi-eye-fill"></i>';
    } else {
        passwordInput.type = "password";
        showPasswordBtn.innerHTML = '<i class="bi bi-eye-slash-fill"></i>';
    }
}
 // Add event listeners to check validation status on input change
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

</body>

</html>
