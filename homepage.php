<!DOCTYPE html>
<html lang="en">
<head>
  <title>HOMEPAGE</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
  <style>
      body.dark-mode {
          background-color: black;
          color: #ffffff;
      }
  </style>
</head>
<body>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <nav class="navbar navbar-expand-sm bg-dark navbar-dark fixed-top">
        <div class="container-md">
            <!-- Brand/logo -->
            <a class="navbar-brand" href="#">
                <img src="images/logo-white.png" alt="PSNACET" height="60">
            </a>

            <!-- Toggler/collapsible Button -->
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <!-- Navbar links -->
            <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link" href="#">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">About</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Contact</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#" data-bs-toggle="modal" data-bs-target="#signupModal">Signup</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="login22.php">Login</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Signup Modal -->
    <div class="modal fade" id="signupModal" tabindex="-1" aria-labelledby="signupModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="signupModalLabel">Select User Type</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <div class="input-group mb-3">
                <span class="input-group-text"><i class="bi bi-person-circle"></i></span>
                <div class="form-floating">
                    <select class="form-select" id="userType" required>
                        <option value="">--Select Role--</option>
                        <option value="staff">👨‍💼 Staff</option>
                        <option value="student">🎓 Student</option>
                    </select>
                    <label for="userType">Select User Type</label>
                </div>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-primary" onclick="redirectUser()">Proceed</button>
          </div>
        </div>
      </div>
    </div>

    <!-- Carousel -->
    <div id="demo" class="carousel slide" data-bs-ride="carousel">
        <!-- Indicators/dots -->
        <div class="carousel-indicators">
            <button type="button" data-bs-target="#demo" data-bs-slide-to="0" class="active"></button>
            <button type="button" data-bs-target="#demo" data-bs-slide-to="1"></button>
            <button type="button" data-bs-target="#demo" data-bs-slide-to="2"></button>
        </div>

        <!-- The slideshow/carousel -->
        <div class="carousel-inner">
            <div class="carousel-item active">
                <img src="images/psna.jpg" alt="Los Angeles" class="d-block" style="width:100%">
            </div>
            <div class="carousel-item">
                <img src="images/psna2.png" alt="Chicago" class="d-block" style="width:100%">
            </div>
            <div class="carousel-item">
                <img src="images/psna.jpg" alt="New York" class="d-block" style="width:100%">
            </div>
        </div>

        <!-- Left and right controls/icons -->
        <button class="carousel-control-prev" type="button" data-bs-target="#demo" data-bs-slide="prev">
            <span class="carousel-control-prev-icon"></span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#demo" data-bs-slide="next">
            <span class="carousel-control-next-icon"></span>
        </button>
    </div>

    <script>
        function redirectUser() {
            var userType = document.getElementById("userType").value;
            if (userType === "staff") {
                window.location.href = "staff_registerform.php";
            } else if (userType === "student") {
                window.location.href = "student_registerform.php";
            } else {
                alert("Please select a user type before proceeding.");
            }
        }
    </script>

</body>
</html>
