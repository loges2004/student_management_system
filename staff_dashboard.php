<?php
// Start session
session_start();

// Include database connection
include('db.php');

// Verify database connection
if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

// Define staff id for testing purpose
$staff_id = "IT2282"; // Replace this with dynamic staff_id if needed

// Fetch staff details
$query = "SELECT * FROM staff WHERE staff_id = ?";
$stmt = $mysqli->prepare($query);

// Check if prepare() succeeded
if (!$stmt) {
    die("Error preparing staff query: " . $mysqli->error);
}

$stmt->bind_param("s", $staff_id);
if (!$stmt->execute()) {
    die("Error executing staff query: " . $stmt->error);
}

$result = $stmt->get_result();
$staff = $result->fetch_assoc();

// Check if staff exists
if ($staff) {
    // Store staff_name in session variable
    $_SESSION['staff_name'] = $staff['staff_name'];
} else {
    die("Staff not found.");
}

// Fetch recent test results
$tests_query = "
    SELECT 
        tr.*,
        COUNT(sm.id) AS total_students,
        AVG(sm.marks) AS average_marks,
        MAX(sm.total_marks) AS highest_score
    FROM test_results tr
    LEFT JOIN student_marks sm ON tr.id = sm.test_id
    WHERE tr.staff_id = ?
    GROUP BY tr.id
    ORDER BY tr.id DESC 
    LIMIT 5
";

$tests_stmt = $mysqli->prepare($tests_query);

if (!$tests_stmt) {
    die("Error preparing tests query: " . $mysqli->error);
}

$tests_stmt->bind_param("s", $staff_id); 
if (!$tests_stmt->execute()) {
    die("Error executing tests query: " . $tests_stmt->error);
}

$tests_result = $tests_stmt->get_result();

// Fetch recent marks
$marks_query = "
    SELECT sm.*, tr.test_type, tr.subject_name 
    FROM student_marks sm
    JOIN test_results tr ON sm.test_id = tr.id
    WHERE tr.staff_id = ?
    ORDER BY sm.id DESC
    LIMIT 5
";

$marks_stmt = $mysqli->prepare($marks_query);

if (!$marks_stmt) {
    die("Error preparing marks query: " . $mysqli->error);
}

$marks_stmt->bind_param("s", $staff_id);
if (!$marks_stmt->execute()) {
    die("Error executing marks query: " . $marks_stmt->error);
}

$marks_result = $marks_stmt->get_result();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Staff Dashboard - College Management System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        :root {
            --primary-color: #2a2a72;
            --secondary-color: #009ffd;
            --accent-color: #ff7f50;
            --light-bg: #f8f9fa;
            --dark-bg: #1a1a1a;
            --dark-text: #ffffff;
            --light-text: #000000;
        }

        body {
            background-color: var(--light-bg);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            color: var(--light-text);
        }

        body.dark-mode {
            background-color: var(--dark-bg);
            color: var(--dark-text);
        }

        .navbar {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            box-shadow: 0 2px 15px rgba(0,0,0,0.1);
        }

        .navbar-brand {
            font-weight: 700;
            letter-spacing: 1px;
            color: white !important;
        }

        .nav-link {
            color: rgba(255,255,255,0.8) !important;
            transition: all 0.3s ease;
        }

        .nav-link:hover {
            color: white !important;
            transform: translateY(-2px);
        }

        .dashboard-header {
            background: linear-gradient(rgba(255,255,255,0.9), rgba(255,255,255,0.9)), url('assets/images/dashboard-bg.png');
            background-size: cover;
            border-radius: 15px;
            padding: 2rem;
            margin: 20px 0;
            box-shadow: 0 4px 20px rgba(0,0,0,0.05);
        }

        .dark-mode .dashboard-header {
            background: linear-gradient(rgba(0,0,0,0.9), rgba(0,0,0,0.9)), url('assets/images/dashboard-bg.png');
            color: var(--dark-text);
        }

        .feature-card {
            background: white;
            border: none;
            border-radius: 15px;
            transition: all 0.3s cubic-bezier(0.4,0,0.2,1);
            min-height: 250px;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .dark-mode .feature-card {
            background: var(--dark-bg);
            color: var(--dark-text);
        }

        .feature-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 12px 25px rgba(0,0,0,0.1);
        }

        .card-icon {
            font-size: 2.5rem;
            margin-bottom: 1rem;
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
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
        }

        .profile-section {
            background: white;
            border-radius: 15px;
            padding: 20px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.05);
        }

        .dark-mode .profile-section {
            background: var(--dark-bg);
            color: var(--dark-text);
        }

        .profile-img {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            border: 3px solid var(--primary-color);
            object-fit: cover;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .animated {
            animation: fadeIn 0.6s ease-out;
        }

        .dark-mode-toggle {
            cursor: pointer;
        }
        .dropdown-submenu {
            display: none;
            position: absolute;
            top: 40px;
            left: 0;
            min-width: 180px;
            background: white;
            border-radius: 5px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            padding: 5px 0;
        }

        .dropdown-submenu.show {
            display: block;
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark fixed-top">
        <div class="container">
            <a class="navbar-brand" href="#">
                <img src="images/psnalogo.jpg" alt="University Logo" class="me-2" style="width: 30px; height: 30px;">
                PSNA College Portal
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="staff_dashboard.php"><i class="fas fa-home"></i> Dashboard</a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="profileDropdown" role="button" data-bs-toggle="dropdown">
                            <i class="fas fa-user-circle"></i> <?php echo htmlspecialchars($staff['first_name'] . ' ' . $staff['last_name']); ?>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li>
                                <a class="dropdown-item" href="#" id="settings-toggle">
                                    <i class="fas fa-cog me-2"></i>Settings
                                </a>
                            </li>
                            <li>
                                <div id="settings-submenu" class="dropdown-menu dropdown-submenu">
                                    <a class="dropdown-item dark-mode-toggle" href="#">
                                        <i class="fas fa-moon me-2"></i>Dark Mode
                                    </a>
                                </div>
                            </li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <a class="dropdown-item text-danger" href="logout.php">
                                    <i class="fas fa-sign-out-alt me-2"></i>Logout
                                </a>
                            </li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Dashboard Content -->
    <div class="container mt-5 pt-5">
        <div class="dashboard-header animated">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h1 class="display-5 fw-bold text-primary">Welcome Back, <?php echo htmlspecialchars($staff['first_name']); ?>!</h1>
                </div>
                <div class="col-md-4 text-center">
                    <img src="<?php echo htmlspecialchars($staff['profile_image']); ?>" alt="Profile Image" class="profile-img">
                </div>
            </div>
        </div>

        <!-- Quick Actions Grid -->
        <div class="row g-4 mt-3 ">
            <div class="col-md-6">
                <div class="feature-card text-center p-4 animated">
                    <i class="fas fa-clipboard-list card-icon"></i>
                    <h3 class="mb-3">Mark Entry</h3>
                    <p class="text-white">Submit and manage student assessment marks</p>
                    <a href="mark_entry.php" class="btn btn-custom">Access Portal</a>
                </div>
            </div>

            <div class="col-md-6">
                <div class="feature-card text-center p-4 animated">
                    <i class="fas fa-calculator card-icon"></i>
                    <h3 class="mb-3">CGPA Calculator</h3>
                    <p class="text-white">Calculate and analyze student performance</p>
                    <a href="dashboard.php?staff_name=<?php echo urlencode($_SESSION['staff_name']); ?>" class="btn btn-custom">Calculate Now</a>
                </div>
            </div>
        </div>

        <!-- Recent Marks Section -->
        <div class="row mt-5">
            <div class="col-md-6">
                <div class="card animated">
                    <div class="card-header bg-primary text-white">
                        <h5 class="card-title mb-0"><i class="fas fa-clipboard-list me-2"></i>Recent Marks</h5>
                    </div>
                    <div class="card-body">
                        <?php if ($marks_result->num_rows > 0): ?>
                            <ul class="list-group list-group-flush">
                                <?php while ($mark = $marks_result->fetch_assoc()): ?>
                                    <li class="list-group-item">
                                        <strong>Student:</strong> <?php echo htmlspecialchars($mark['student_name']); ?><br>
                                        <strong>Test:</strong> <?php echo htmlspecialchars($mark['test_type']); ?><br>
                                        <strong>Marks:</strong> <?php echo htmlspecialchars($mark['marks']); ?>
                                    </li>
                                <?php endwhile; ?>
                            </ul>
                        <?php else: ?>
                            <p class="text-black">No recent marks found.</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- Recent Test Results Section -->
            <div class="col-md-6">
                <div class="card animated">
                    <div class="card-header bg-primary text-white">
                        <h5 class="card-title mb-0"><i class="fas fa-chart-line me-2"></i>Recent Test Results</h5>
                    </div>
                    <div class="card-body">
                        <?php if ($tests_result->num_rows > 0): ?>
                            <ul class="list-group list-group-flush">
                                <?php while ($test = $tests_result->fetch_assoc()): ?>
                                    <li class="list-group-item">
                                        <strong>Test:</strong> <?php echo htmlspecialchars($test['test_type']); ?><br>
                                        <strong>Subject:</strong> <?php echo htmlspecialchars($test['subject_name']); ?><br>
                                        <strong>Max Marks:</strong> <?php echo htmlspecialchars($test['testmark']); ?>
                                    </li>
                                <?php endwhile; ?>
                            </ul>
                        <?php else: ?>
                            <p class="text-black">No recent test results found.</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
    // Dark Mode Toggle
    const darkModeToggle = document.querySelector('.dark-mode-toggle');
    const body = document.body;

    darkModeToggle.addEventListener('click', () => {
        body.classList.toggle('dark-mode');
        const isDarkMode = body.classList.contains('dark-mode');
        localStorage.setItem('darkMode', isDarkMode);

        // Update text colors based on mode
        updateTextColors(isDarkMode);
    });

    // Check localStorage for dark mode preference
    const savedDarkMode = localStorage.getItem('darkMode');
    if (savedDarkMode === 'true') {
        body.classList.add('dark-mode');
        updateTextColors(true); // Update text colors for dark mode
    } else {
        updateTextColors(false); // Update text colors for normal mode
    }

    // Function to update text colors based on mode
    function updateTextColors(isDarkMode) {
        const textWhiteElements = document.querySelectorAll('.text-white');
        const textMutedElements = document.querySelectorAll('.text-muted');

        if (isDarkMode) {
            // In dark mode, change .text-muted to .text-white
            textMutedElements.forEach(element => {
                element.classList.remove('text-muted');
                element.classList.add('text-white');
            });
        } else {
            // In normal mode, change .text-white to .text-muted
            textWhiteElements.forEach(element => {
                element.classList.remove('text-white');
                element.classList.add('text-muted');
            });
        }
    }

    // Settings Dropdown Toggle
    document.addEventListener("DOMContentLoaded", function () {
        const settingsToggle = document.getElementById("settings-toggle");
        const settingsSubmenu = document.getElementById("settings-submenu");

        settingsToggle.addEventListener("click", function (event) {
            event.preventDefault();
            event.stopPropagation(); // Prevent event bubbling
            settingsSubmenu.classList.toggle("show");
        });

        // Close submenu when clicking outside
        document.addEventListener("click", function (event) {
            if (!settingsToggle.contains(event.target)) {
                settingsSubmenu.classList.remove("show");
            }
        });
    });
</script>
</body>
</html>