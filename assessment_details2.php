<?php
// Include your database connection
include('db.php');

// Retrieve the required parameters from the previous page via GET or session
$year = isset($_GET['year']) ? $_GET['year'] : '';
$semester = isset($_GET['semester']) ? $_GET['semester'] : '';
$department = isset($_GET['department']) ? $_GET['department'] : '';
$test_type = isset($_GET['test_type']) ? $_GET['test_type'] : '';
$subject_name = isset($_GET['subject_name']) ? $_GET['subject_name'] : '';
$subject_code = isset($_GET['subject_code']) ? $_GET['subject_code'] : '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Assessment Details</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <style>
        table {
            font-family: Arial, sans-serif;
            border-collapse: collapse;
            width: 100%;
            margin: 20px 0;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            padding: 20px;
        }

        td, th {
            border: 1px solid #f4ecf4;
            text-align: center;
            padding: 8px;
        }

        tr:nth-child(even) {
            background-color: #dddddd;
        }

        h2 {
            text-align: center;
            font-size: large;
        }

        h1 {
            text-align: center;
            font-size: medium;
        }

        .form-select {
            width: 100%;
        }

        .form-group label {
            text-align: left;
            display: block;
            margin-bottom: 5px;
        }

        .row {
            display: flex;
        }

        .col-md-6 {
            flex: 1;
            padding: 10px;
        }

        .details-table {
            width: 60%;
            margin: 20px auto;
            background-color: #f4f4f4;
            border-radius: 10px;
            padding: 10px;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
        }

        .details-table td, .details-table th {
            text-align: left;
            padding: 8px;
            border: none;
        }
    </style>
</head>
<body>
<div class="container mt-5">
    <!-- Course Details Table -->
    <table class="details-table table table-borderless">
        <tr>
            <th>Year:</th>
            <td><?php echo htmlspecialchars($year); ?></td>
            <th>Semester:</th>
            <td><?php echo htmlspecialchars($semester); ?></td>
        </tr>
        <tr>
            <th>Department:</th>
            <td><?php echo htmlspecialchars($department); ?></td>
            <th>Test Type:</th>
            <td><?php echo htmlspecialchars($test_type); ?></td>
        </tr>
        <tr>
            <th>Subject Name:</th>
            <td><?php echo htmlspecialchars($subject_name); ?></td>
            <th>Subject Code:</th>
            <td><?php echo htmlspecialchars($subject_code); ?></td>
        </tr>
    </table>

    <h2>Set Course Outcomes for the Subjects</h2>
    <form action="submit_assessment2.php" method="POST" id="assessmentForm">
        <input type="hidden" name="year" value="<?php echo htmlspecialchars($year); ?>">
        <input type="hidden" name="semester" value="<?php echo htmlspecialchars($semester); ?>">
        <input type="hidden" name="department" value="<?php echo htmlspecialchars($department); ?>">
        <input type="hidden" name="test_type" value="<?php echo htmlspecialchars($test_type); ?>">
        <input type="hidden" name="subject_name" value="<?php echo htmlspecialchars($subject_name); ?>">
        <input type="hidden" name="subject_code" value="<?php echo htmlspecialchars($subject_code); ?>">
        
        <table class="table">
            <tr>
                <td class="part">
                    <h1>Part A</h1>
                    <table class="table">
                        <?php for ($i = 1; $i <= 10; $i++): ?>
                            <tr>
                                <td>Question <?php echo $i; ?>:</td>
                                <td>
                                    <select class="form-select" name="co_question_<?php echo $i; ?>" required>
                                        <option value="">--Select CO Number for Question <?php echo $i; ?>--</option>
                                        <?php for ($j = 1; $j <= 5; $j++): ?>
                                            <option value="CO<?php echo $j; ?>">CO<?php echo $j; ?></option>
                                        <?php endfor; ?>
                                    </select>
                                </td>
                            </tr>
                        <?php endfor; ?>
                    </table>
                </td>
                <td class="part">
                    <h1>Part B</h1>
                    <table class="table">
                        <?php for ($i = 11; $i <= 16; $i++): ?>
                            <?php if ($i <= 16): ?>
                                <tr>
                                    <td>Question <?php echo $i; ?>a:</td>
                                    <td>
                                        <select class="form-select" name="co_question_<?php echo $i; ?>a" required>
                                            <option value="">--Select CO Number for Question <?php echo $i; ?>a--</option>
                                            <?php for ($j = 1; $j <= 5; $j++): ?>
                                                <option value="CO<?php echo $j; ?>">CO<?php echo $j; ?></option>
                                            <?php endfor; ?>
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Question <?php echo $i; ?>b:</td>
                                    <td>
                                        <select class="form-select" name="co_question_<?php echo $i; ?>b" required>
                                            <option value="">--Select CO Number for Question <?php echo $i; ?>b--</option>
                                            <?php for ($j = 1; $j <= 5; $j++): ?>
                                                <option value="CO<?php echo $j; ?>">CO<?php echo $j; ?></option>
                                            <?php endfor; ?>
                                        </select>
                                    </td>
                                </tr>
                            <?php endif; ?>
                        <?php endfor; ?>
                    </table>
                </td>
            </tr>
            <tr>
                <td colspan="2">
                    <button type="submit" class="btn btn-primary">Submit Assessment</button>
                </td>
            </tr>
        </table>
    </form>
</div>
</body>
</html>
