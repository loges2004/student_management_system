<?php
// Include your database connection
include('db.php');

// Assuming you are receiving the following from the previous page via GET or session
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
            width: 100%; /* Full width for the main table */
            margin: 20px 0; /* Margin between tables */
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
            width: 100%; /* Full width for select elements */
        }

        /* Custom styles to center-align the labels */
        .form-group label {
            text-align: left; /* Center-align text */
            display: block; /* Make labels block elements */
            margin-bottom: 5px; /* Add some space below the labels */
        }

        /* Flexbox for equal width layout */
        .row {
            display: flex;
        }

        .col-md-6 {
            flex: 1; /* Equal width for both columns */
            padding: 10px; /* Add padding for spacing */
        }
    </style>
</head>
<body>
<div class="container mt-5">
    <h2>Set Course Outcomes for the Subjects</h2>
    <form action="submit_assessment.php" method="POST" id="assessmentForm">
        <table class="table">
            <tr>
                <td colspan="2">
                    <div class="form-group">
                        <label for="year">Year:</label>
                        <input type="text" class="form-control" id="year" name="year" value="<?php echo htmlspecialchars($year); ?>" readonly>
                    </div>
                </td>
            </tr>
            <tr>
                <td colspan="2">
                    <div class="form-group">
                        <label for="semester">Semester:</label>
                        <input type="text" class="form-control" id="semester" name="semester" value="<?php echo htmlspecialchars($semester); ?>" readonly>
                    </div>
                </td>
            </tr>
            <tr>
                <td colspan="2">
                    <div class="form-group">
                        <label for="department">Department:</label>
                        <input type="text" class="form-control" id="department" name="department" value="<?php echo htmlspecialchars($department); ?>" readonly>
                    </div>
                </td>
            </tr>
            <tr>
                <td colspan="2">
                    <div class="form-group">
                        <label for="test_type">Test Type:</label>
                        <input type="text" class="form-control" id="test_type" name="test_type" value="<?php echo htmlspecialchars($test_type); ?>" readonly>
                    </div>
                </td>
            </tr>
            <tr>
                <td colspan="2">
                    <div class="form-group">
                        <label for="subject_name">Subject Name:</label>
                        <input type="text" class="form-control" id="subject_name" name="subject_name" value="<?php echo htmlspecialchars($subject_name); ?>" readonly>
                    </div>
                </td>
            </tr>
            <tr>
                <td colspan="2">
                    <div class="form-group">
                        <label for="subject_code">Subject Code:</label>
                        <input type="text" class="form-control" id="subject_code" name="subject_code" value="<?php echo htmlspecialchars($subject_code); ?>" readonly>
                    </div>
                </td>
            </tr>

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
                        <?php for ($i = 11; $i <= 13; $i++): ?>
                            <?php if ($i == 11): ?>
                                <tr>
                                    <td>Question 11a:</td>
                                    <td>
                                        <select class="form-select" name="co_question_11a" required>
                                            <option value="">--Select CO Number for Question 11a--</option>
                                            <?php for ($j = 1; $j <= 5; $j++): ?>
                                                <option value="CO<?php echo $j; ?>">CO<?php echo $j; ?></option>
                                            <?php endfor; ?>
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Question 11b:</td>
                                    <td>
                                        <select class="form-select" name="co_question_11b" required>
                                            <option value="">--Select CO Number for Question 11b--</option>
                                            <?php for ($j = 1; $j <= 5; $j++): ?>
                                                <option value="CO<?php echo $j; ?>">CO<?php echo $j; ?></option>
                                            <?php endfor; ?>
                                        </select>
                                    </td>
                                </tr>
                            <?php elseif ($i == 12): ?>
                                <tr>
                                    <td>Question 12a:</td>
                                    <td>
                                        <select class="form-select" name="co_question_12a" required>
                                            <option value="">--Select CO Number for Question 12a--</option>
                                            <?php for ($j = 1; $j <= 5; $j++): ?>
                                                <option value="CO<?php echo $j; ?>">CO<?php echo $j; ?></option>
                                            <?php endfor; ?>
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Question 12b:</td>
                                    <td>
                                        <select class="form-select" name="co_question_12b" required>
                                            <option value="">--Select CO Number for Question 12b--</option>
                                            <?php for ($j = 1; $j <= 5; $j++): ?>
                                                <option value="CO<?php echo $j; ?>">CO<?php echo $j; ?></option>
                                            <?php endfor; ?>
                                        </select>
                                    </td>
                                </tr>
                            <?php elseif ($i == 13): ?>
                                <tr>
                                    <td>Question 13a:</td>
                                    <td>
                                        <select class="form-select" name="co_question_13a" required>
                                            <option value="">--Select CO Number for Question 13a--</option>
                                            <?php for ($j = 1; $j <= 5; $j++): ?>
                                                <option value="CO<?php echo $j; ?>">CO<?php echo $j; ?></option>
                                            <?php endfor; ?>
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Question 13b:</td>
                                    <td>
                                        <select class="form-select" name="co_question_13b" required>
                                            <option value="">--Select CO Number for Question 13b--</option>
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
