
<?php
session_start();

if (isset($_SESSION['success'])) {
    echo "
    <div class='alert alert-success alert-dismissible fade show' role='alert' style='position: fixed; top: 10px; left: 50%; transform: translateX(-50%); z-index: 1050;'>
        " . $_SESSION['success'] . "
        <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
    </div>
    <script>
        setTimeout(() => {
            document.querySelector('.alert-success').remove();
        }, 3000);
    </script>
    ";
    unset($_SESSION['success']);
}

if (isset($_SESSION['failed'])) {
    echo "
    <div class='alert alert-danger alert-dismissible fade show' role='alert' style='position: fixed; top: 10px; left: 50%; transform: translateX(-50%); z-index: 1050;'>
        " . $_SESSION['failed'] . "
        <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
    </div>
    <script>
        setTimeout(() => {
            document.querySelector('.alert-danger').remove();
        }, 3000);
    </script>
    ";
    unset($_SESSION['failed']);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New Department</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        :root {
            --primary-color: #2a2a72;
            --secondary-color: #009ffd;
            --accent-color: #ff7f50;
            --light-bg: #f8f9fa;
        }

        body {
            background-color: var(--light-bg);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .form-container {
            background: white;
            border-radius: 15px;
            padding: 2rem;
            margin: 20px auto;
            max-width: 800px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
        }

        .form-container h2 {
            color: var(--primary-color);
            margin-bottom: 1.5rem;
            font-weight: 700;
        }

        .form-label {
            font-weight: 600;
            color: var(--primary-color);
        }

        .btn-custom {
            background: linear-gradient(45deg, var(--primary-color), var(--secondary-color));
            color: white !important;
            border: none;
            padding: 10px 25px;
            border-radius: 25px;
            transition: all 0.3s ease;
        }

        .btn-custom:hover {
            transform: scale(1.05);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
        }
    </style>
</head>
<body>

<div class="container mt-5">
    <div class="form-container">
        <h2>Add New Department</h2>
        <form id="departmentForm" action="save_department.php" method="POST">
            <!-- Department ID -->
            <div class="mb-3">
                <label for="departmentId" class="form-label">Department ID</label>
                <input type="text" class="form-control" id="departmentId" name="departmentId" required>
            </div>

            <!-- Program Type -->
            <div class="mb-3">
                <label for="programType" class="form-label">Program Type</label>
                <select class="form-select" id="programType" name="programType" required>
                    <option value="">-- Select Program Type --</option>
                    <option value="UG">UG</option>
                    <option value="PG">PG</option>
                </select>
            </div>

            <!-- Degree Type (Dynamic) -->
            <div class="mb-3" id="degreeTypeContainer" style="display: none;">
                <label for="degreeType" class="form-label">Degree Type</label>
                <select class="form-select" id="degreeType" name="degreeType" required>
                    <option value="">-- Select Degree Type --</option>
                </select>
            </div>

            <!-- Department Name (Replaces Specialization) -->
            <div class="mb-3">
                <label for="departmentName" class="form-label">Department Name</label>
                <input type="text" class="form-control" id="departmentName" name="departmentName" 
                       placeholder="e.g., Information Technology" required>
            </div>

            <!-- Year -->
            <div class="mb-3">
                <label for="year" class="form-label">Year</label>
                <input type="number" class="form-control" id="year" name="year" min="1" max="5" required>
            </div>
            <button type="button" class="btn btn-danger me-5" onclick="window.location.href='department_manage.php'">back</button>

            <!-- Submit Button -->
            <button type="submit" class="btn btn-custom w-75 ms-5">Save Department</button>
        </form>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    const programType = document.getElementById('programType');
    const degreeType = document.getElementById('degreeType');
    const degreeTypeContainer = document.getElementById('degreeTypeContainer');

    // Degree type options
    const ugDegrees = ["BE", "B.TECH"];
    const pgDegrees = ["M.TECH", "MBA", "MCA", "PhD"];

    // Event listener for program type change
    programType.addEventListener('change', function() {
        degreeType.innerHTML = '<option value="">-- Select Degree Type --</option>';
        
        if (programType.value === "UG") {
            degreeTypeContainer.style.display = 'block';
            ugDegrees.forEach(degree => {
                degreeType.innerHTML += `<option value="${degree}">${degree}</option>`;
            });
        } else if (programType.value === "PG") {
            degreeTypeContainer.style.display = 'block';
            pgDegrees.forEach(degree => {
                degreeType.innerHTML += `<option value="${degree}">${degree}</option>`;
            });
        } else {
            degreeTypeContainer.style.display = 'none';
        }
    });
</script>

</body>
</html>