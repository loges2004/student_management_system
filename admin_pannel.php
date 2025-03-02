<?php
include('db.php'); // Include database connection
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <!-- Custom CSS -->
    <style>
        body {
            background-color: #f8f9fa;
        }
        .sidebar {
            height: 100vh;
            background: linear-gradient(135deg, #6a11cb, #2575fc);
            color: white;
            padding: 20px;
        }
        .sidebar a {
            color: white;
            text-decoration: none;
            display: block;
            padding: 10px;
            margin: 5px 0;
            border-radius: 5px;
            transition: background 0.3s;
        }
        .sidebar a:hover {
            background: rgba(255, 255, 255, 0.1);
        }
        .sidebar a i {
            margin-right: 10px;
        }
        .main-content {
            padding: 20px;
        }
        .card {
            border: none;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s;
        }
        .card:hover {
            transform: translateY(-5px);
        }
        .card-header {
            background: linear-gradient(135deg, #6a11cb, #2575fc);
            color: white;
            border-radius: 10px 10px 0 0;
        }
        .card-body {
            background: white;
            border-radius: 0 0 10px 10px;
        }
        .chart-container {
            width: 100%;
            height: 400px;
            margin: 20px 0;
        }
    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-md-3 sidebar">
                <h3 class="text-center mb-4">Admin Panel</h3>
                <a href="staff_entry.php">
                    <i class="fas fa-users"></i> Staff Management
                </a>
                <a href="student_entry.php">
                    <i class="fas fa-user-graduate"></i> Student Management
                </a>
                <a href="subject_entry.php">
                    <i class="fas fa-book"></i> Subject Management
                </a>
                <a href="department_entry.php">
                    <i class="fas fa-building"></i> Department Management
                </a>
            </div>

            <!-- Main Content -->
            <div class="col-md-9 main-content">
                <div class="row">
                    <div class="col-md-12">
                        <h2>Welcome to Admin Panel</h2>
                        <p>Manage your institution's staff, students, subjects, and departments efficiently.</p>
                    </div>
                </div>

                <!-- Charts -->
                <div class="row mt-4">
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">
                                <i class="fas fa-chart-bar"></i> Staff Distribution by Department
                            </div>
                            <div class="card-body">
                                <div class="chart-container">
                                    <canvas id="staffChart"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">
                                <i class="fas fa-chart-pie"></i> Student Distribution by Year
                            </div>
                            <div class="card-body">
                                <div class="chart-container">
                                    <canvas id="studentChart"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Tables -->
                <div class="row mt-4">
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">
                                <i class="fas fa-table"></i> Recent Staff
                            </div>
                            <div class="card-body">
                                <div id="staffTable"></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">
                                <i class="fas fa-table"></i> Recent Students
                            </div>
                            <div class="card-body">
                                <div id="studentTable"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Custom JS -->
    <script>
    // Fetch data for charts and tables
    async function fetchData() {
        try {
            const response = await fetch('fetch_data.php');
            if (!response.ok) {
                throw new Error('Failed to fetch data');
            }
            const rawResponse = await response.text(); // Log raw response
            console.log('Raw Response:', rawResponse);
            const data = JSON.parse(rawResponse); // Parse JSON
            return data;
        } catch (error) {
            console.error('Error fetching data:', error);
            return null;
        }
    }

    // Render Staff Chart
    async function renderStaffChart() {
        const data = await fetchData();
        if (!data || !data.staff || !data.staff.labels || !data.staff.values) {
            console.error('Invalid or missing staff data');
            return;
        }

        const ctx = document.getElementById('staffChart').getContext('2d');
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: data.staff.labels,
                datasets: [{
                    label: 'Staff Count',
                    data: data.staff.values,
                    backgroundColor: 'rgba(75, 192, 192, 0.2)',
                    borderColor: 'rgba(75, 192, 192, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    y: { beginAtZero: true }
                }
            }
        });
    }

    // Render Student Chart
    async function renderStudentChart() {
        const data = await fetchData();
        if (!data || !data.student || !data.student.labels || !data.student.values) {
            console.error('Invalid or missing student data');
            return;
        }

        const ctx = document.getElementById('studentChart').getContext('2d');
        new Chart(ctx, {
            type: 'pie',
            data: {
                labels: data.student.labels,
                datasets: [{
                    label: 'Student Count',
                    data: data.student.values,
                    backgroundColor: [
                        'rgba(255, 99, 132, 0.2)',
                        'rgba(54, 162, 235, 0.2)',
                        'rgba(255, 206, 86, 0.2)',
                        'rgba(75, 192, 192, 0.2)'
                    ],
                    borderColor: [
                        'rgba(255, 99, 132, 1)',
                        'rgba(54, 162, 235, 1)',
                        'rgba(255, 206, 86, 1)',
                        'rgba(75, 192, 192, 1)'
                    ],
                    borderWidth: 1
                }]
            }
        });
    }

    // Render Staff Table
    async function renderStaffTable() {
        const data = await fetchData();
        if (!data || !data.recentStaff) {
            console.error('Invalid or missing recent staff data');
            return;
        }

        const table = document.getElementById('staffTable');
        table.innerHTML = `
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Staff ID</th>
                        <th>Name</th>
                        <th>Department</th>
                    </tr>
                </thead>
                <tbody>
                    ${data.recentStaff.map(staff => `
                        <tr>
                            <td>${staff.staff_id}</td>
                            <td>${staff.first_name} ${staff.last_name}</td>
                            <td>${staff.department}</td>
                        </tr>
                    `).join('')}
                </tbody>
            </table>
        `;
    }

    // Render Student Table
    async function renderStudentTable() {
        const data = await fetchData();
        if (!data || !data.recentStudents) {
            console.error('Invalid or missing recent student data');
            return;
        }

        const table = document.getElementById('studentTable');
        table.innerHTML = `
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Register No</th>
                        <th>Name</th>
                        <th>Department</th>
                    </tr>
                </thead>
                <tbody>
                    ${data.recentStudents.map(student => `
                        <tr>
                            <td>${student.register_no}</td>
                            <td>${student.student_name}</td>
                            <td>${student.department}</td>
                        </tr>
                    `).join('')}
                </tbody>
            </table>
        `;
    }

    // Initialize
    document.addEventListener('DOMContentLoaded', function () {
        renderStaffChart();
        renderStudentChart();
        renderStaffTable();
        renderStudentTable();
    });
</script>
</body>
</html>