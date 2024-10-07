<div class="form-group">
            <label for="register_no">Register Number:</label>
            <input type="text" class="form-control" id="register_no" name="register_no" required>
        </div>
        <div class="form-group">
            <label for="student_name">Student Name:</label>
            <input type="text" class="form-control" id="student_name" name="student_name" readonly>
        </div>
        <div class="form-group">
            <label for="student_department">Student Department:</label>
            <input type="text" class="form-control" id="student_department" name="student_department" readonly>
        </div>

        <script>
                // Fetch student details when register number is entered
    $('#register_no').on('change', function() {
        var registerNo = $(this).val();
        if (registerNo) {
            $.ajax({
                url: 'fetch_stud.php', // The URL to the PHP script that fetches student details
                type: 'POST',
                data: {register_no: registerNo},
                success: function(data) {
                    var student = JSON.parse(data);
                    if (student) {
                        $('#student_name').val(student.student_name);
                        $('#student_department').val(student.department);
                    } else {
                        alert('Student not found');
                    }
                }
            });
        } else {
            $('#student_name').val('');
            $('#student_department').val('');
        }
    });

    // Calculate total marks
    function calculateTotal() {
        var total = 0;
        for (var i = 1; i <= 13; i++) {
            var marks = parseFloat($('#q' + i + '_marks').val()) || 0;
            var co = parseFloat($('#q' + i + '_co').val()) || 0;
            total += marks * co; // Assuming total is calculated as marks * coefficient
        }
        $('#total_marks').val(total);
    }
</script>
        </script>

        <!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Exam Mark Entry</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
    <style>
        table {
            font-family: Arial, sans-serif;
            border-collapse: collapse;
            width: 40%;
            margin: 50px auto;
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

        h1 {
            text-align: center;
        }

        .small {
            font-size: 10px;
        }

        .form-select {
            width: 350px; /* Adjust width as needed */
        }
    </style>
</head>
<body>
<div class="container">
    <form method="post">
        <table class="table">
            <tr>
                <th colspan="2"><h1>Exam Mark Entry</h1></th>
            </tr>
            <tr>
                <td>Subject Name:</td>
                <td>
                    <select id="subject_name" name="subject_name" class="form-control" required>
                        <option value="">--Select Subject--</option>
                    </select>
                </td>
            </tr>
            <tr>
                <td>Test Serial:</td>
                <td>
                    <select id="test_serial" name="test_serial" class="form-control" required>
                        <option value="">--Select Test Serial--</option>
                    </select>
                </td>
            </tr>
            <?php for ($i = 1; $i <= 16; $i++): ?>
                <tr>
                    <td>Question <?php echo ($i > 10) ? $i.'a' : $i; ?>:</td>
                    <td>
                        <select class="form-select" name="co_question_<?php echo $i; ?>" required>
                            <option value="">--Select CO Number for Question <?php echo ($i > 10) ? $i.'a' : $i; ?>--</option>
                            <?php for ($j = 1; $j <= 5; $j++): ?>
                                <option value="CO<?php echo $j; ?>">CO<?php echo $j; ?></option>
                            <?php endfor; ?>
                        </select>
                    </td>
                </tr>
            <?php endfor; ?>
            <tr>
                <td colspan="2">
                    <div class="btn-group">
                        <input class="btn btn-secondary" type="reset" value="Clear">
                        <input class="btn btn-danger" type="button" value="Cancel" onclick="cancelForm()">
                        <input class="btn btn-success" type="submit" name="save" value="Save">
                    </div>
                </td>
            </tr>
        </table>
    </form>
</div>

<script>
    function cancelForm() {
        window.location.href = "datastructure.php";
    }

    // Populate Subject Name and Test Serial dropdowns based on content
    window.onload = function () {
        var subjectDropdown = document.getElementById("subject_name");
        var testSerialDropdown = document.getElementById("test_serial");

        // Define the subjects and test serials
        var subjects = ["DBMS", "DS", "OOSE", "OOPS", "EVS", "WE", "DM", "FDS"];
        var testSerials = ["SerialTest1", "SerialTest2"];

        // Populate Subject Name dropdown
        for (var i = 0; i < subjects.length; i++) {
            var option = document.createElement("option");
            option.text = subjects[i];
            option.value = subjects[i];
            subjectDropdown.add(option);
        }

        // Populate Test Serial dropdown
        for (var j = 0; j < testSerials.length; j++) {
            var option = document.createElement("option");
            option.text = testSerials[j];
            option.value = testSerials[j];
            testSerialDropdown.add(option);
        }
    };
</script>

<?php
if (isset($_POST['save'])) {
    // Retrieve form data including subject name and test serial
    $subject_name = $_POST['subject_name'];
    $test_serial = $_POST['test_serial'];

    // Initialize an empty array to store CO data
    $co_data = [];

    // Retrieve CO data for each question
    for ($i = 1; $i <= 16; $i++) {
        $co_question_key = 'co_question_' . $i;
        if (isset($_POST[$co_question_key])) {
            $co_data[$i] = $_POST[$co_question_key];
        }
    }

    // Database connection
    $hostname = "localhost";
    $username = "root";
    $password = "";
    $databasename = "register_db";
    $mysqli = mysqli_connect($hostname, $username, $password, $databasename);

    // Check connection
    if (!$mysqli) {
        die("Connection failed: " . mysqli_connect_error());
    }

    // Insert data into the database
    foreach ($co_data as $question_number => $co_number) {
        $marks = $_POST['marks_' . $question_number];
        $insert_query = "INSERT INTO marks (subject_name, test_serial, question_number, co_number, marks) 
                         VALUES ('$subject_name', '$test_serial', 'Q$question_number', '$co_number', '$marks')";

        if (mysqli_query($mysqli, $insert_query)) {
            echo '<script>';
            echo 'Swal.fire({';
            echo '  icon: "success",';
            echo '  title: "Success",';
            echo '  text: "Record inserted successfully",';
            echo '})';
            echo '</script>';
        }else {
            echo '<script>';
            echo 'Swal.fire({';
            echo '  icon: "error",';
            echo '  title: "Error",';
            echo '  text: "Error inserting record: ' . mysqli_error($mysqli) . '",';
            echo '});';
            echo '</script>';
        }
    }

    // Close database connection
    mysqli_close($mysqli);
}
?>
</body>
</html>
<?php
// submit_assessment.php
include('db.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $year = $_POST['year'];
    $semester = $_POST['semester'];
    $department = $_POST['department'];
    $test_type = $_POST['test_type'];
    $subject_name = $_POST['subject_name'];
    $subject_code = $_POST['subject_code'];
    $register_no = $_POST['register_no'];
    $student_name = $_POST['student_name'];
    $student_department = $_POST['student_department'];

    // Marks and coefficients (adjust according to your form structure)
    $q_marks = [];
    $q_co = [];
    for ($i = 1; $i <= 13; $i++) {
        $q_marks[$i] = $_POST["q{$i}_marks"] ?? 0;
        $q_co[$i] = $_POST["q{$i}_co"] ?? 0;
    }

    // Calculate total marks
    $total_marks = array_sum($q_marks);

    // Prepare the insert or update query
    if ($test_type === 'SerialTest1') {
        // Check if record exists
        $stmt = $conn->prepare("SELECT * FROM serialtest1 WHERE register_no = ? AND subject_code = ?");
        $stmt->bind_param("ss", $register_no, $subject_code);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            // Record exists, perform an update
            $stmt = $conn->prepare("UPDATE serialtest1 SET year = ?, semester = ?, department = ?, subject_name = ?, 
            q1_marks = ?, q1_co = ?, q2_marks = ?, q2_co = ?, q3_marks = ?, q3_co = ?, q4_marks = ?, q4_co = ?, 
            q5_marks = ?, q5_co = ?, q6_marks = ?, q6_co = ?, q7_marks = ?, q7_co = ?, q8_marks = ?, q8_co = ?, 
            q9_marks = ?, q9_co = ?, q10_marks = ?, q10_co = ?, q11A_or_11B_marks = ?, q11A_or_11B_co = ?, 
            q12A_or_12B_marks = ?, q12A_or_12B_co = ?, q13A_or_13B_marks = ?, q13A_or_13B_co = ?, 
            total_marks = ? WHERE register_no = ? AND subject_code = ?");
            $stmt->bind_param("ssssssssssssssssssssssssssssssssss", 
                $year, $semester, $department, $subject_name, 
                $q_marks[1], $q_co[1], $q_marks[2], $q_co[2], 
                $q_marks[3], $q_co[3], $q_marks[4], $q_co[4], 
                $q_marks[5], $q_co[5], $q_marks[6], $q_co[6], 
                $q_marks[7], $q_co[7], $q_marks[8], $q_co[8], 
                $q_marks[9], $q_co[9], $q_marks[10], $q_co[10], 
                $q_marks[11], $q_co[11], $q_marks[12], $q_co[12], 
                $q_marks[13], $q_co[13], $total_marks, $register_no, $subject_code);
        } else {
            // No record exists, perform an insert
            $stmt = $conn->prepare("INSERT INTO serialtest1 (year, semester, department, subject_name, 
            register_no, student_name, subject_code, q1_marks, q1_co, q2_marks, q2_co, q3_marks, q3_co, 
            q4_marks, q4_co, q5_marks, q5_co, q6_marks, q6_co, q7_marks, q7_co, q8_marks, q8_co, 
            q9_marks, q9_co, q10_marks, q10_co, q11A_or_11B_marks, q11A_or_11B_co, 
            q12A_or_12B_marks, q12A_or_12B_co, q13A_or_13B_marks, q13A_or_13B_co, total_marks) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("ssssssssssssssssssssssssssssssssss", 
                $year, $semester, $department, $subject_name, 
                $register_no, $student_name, $subject_code, 
                $q_marks[1], $q_co[1], $q_marks[2], $q_co[2], 
                $q_marks[3], $q_co[3], $q_marks[4], $q_co[4], 
                $q_marks[5], $q_co[5], $q_marks[6], $q_co[6], 
                $q_marks[7], $q_co[7], $q_marks[8], $q_co[8], 
                $q_marks[9], $q_co[9], $q_marks[10], $q_co[10], 
                $q_marks[11], $q_co[11], $q_marks[12], $q_co[12], 
                $q_marks[13], $q_co[13], $total_marks);
        }
    } else {
        // Similar logic for SerialTest2
        // Check if record exists
        $stmt = $conn->prepare("SELECT * FROM serialtest2 WHERE register_no = ? AND subject_code = ?");
        $stmt->bind_param("ss", $register_no, $subject_code);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            // Record exists, perform an update
            $stmt = $conn->prepare("UPDATE serialtest2 SET year = ?, semester = ?, department = ?, subject_name = ?, 
            q1_marks = ?, q1_co = ?, q2_marks = ?, q2_co = ?, q3_marks = ?, q3_co = ?, q4_marks = ?, q4_co = ?, 
            q5_marks = ?, q5_co = ?, q6_marks = ?, q6_co = ?, q7_marks = ?, q7_co = ?, q8_marks = ?, q8_co = ?, 
            q9_marks = ?, q9_co = ?, q10_marks = ?, q10_co = ?, q11A_or_11B_marks = ?, q11A_or_11B_co = ?, 
            q12A_or_12B_marks = ?, q12A_or_12B_co = ?, q13A_or_13B_marks = ?, q13A_or_13B_co = ?, 
            total_marks = ? WHERE register_no = ? AND subject_code = ?");
            $stmt->bind_param("ssssssssssssssssssssssssssssssssss", 
                $year, $semester, $department, $subject_name, 
                $q_marks[1], $q_co[1], $q_marks[2], $q_co[2], 
                $q_marks[3], $q_co[3], $q_marks[4], $q_co[4], 
                $q_marks[5], $q_co[5], $q_marks[6], $q_co[6], 
                $q_marks[7], $q_co[7], $q_marks[8], $q_co[8], 
                $q_marks[9], $q_co[9], $q_marks[10], $q_co[10], 
                $q_marks[11], $q_co[11], $q_marks[12], $q_co[12], 
                $q_marks[13], $q_co[13], $total_marks, $register_no, $subject_code);
        } else {
            // No record exists, perform an insert
            $stmt = $conn->prepare("INSERT INTO serialtest2 (year, semester, department, subject_name, 
            register_no, student_name, subject_code, q1_marks, q1_co, q2_marks, q2_co, q3_marks, q3_co, 
            q4_marks, q4_co, q5_marks, q5_co, q6_marks, q6_co, q7_marks, q7_co, q8_marks, q8_co, 
            q9_marks, q9_co, q10_marks, q10_co, q11A_or_11B_marks, q11A_or_11B_co, 
            q12A_or_12B_marks, q12A_or_12B_co, q13A_or_13B_marks, q13A_or_13B_co, total_marks) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("ssssssssssssssssssssssssssssssssss", 
                $year, $semester, $department, $subject_name, 
                $register_no, $student_name, $subject_code, 
                $q_marks[1], $q_co[1], $q_marks[2], $q_co[2], 
                $q_marks[3], $q_co[3], $q_marks[4], $q_co[4], 
                $q_marks[5], $q_co[5], $q_marks[6], $q_co[6], 
                $q_marks[7], $q_co[7], $q_marks[8], $q_co[8], 
                $q_marks[9], $q_co[9], $q_marks[10], $q_co[10], 
                $q_marks[11], $q_co[11], $q_marks[12], $q_co[12], 
                $q_marks[13], $q_co[13], $total_marks);
        }
    }

    // Execute the statement
    if ($stmt->execute()) {
        echo '<div class="alert alert-success mt-3">Assessment submitted successfully!</div>';
    } else {
        echo '<div class="alert alert-danger mt-3">Error submitting assessment: ' . $stmt->error . '</div>';
    }


    // Close statement and connection
    $stmt->close();
    $conn->close();
}
?>
    echo "Question: $question, Marks: " . var_export($marks[$question], true) . ", CO Marks: " . var_export($co_marks[$question], true) . "<br>";
