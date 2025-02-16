<?php
include('db.php');
header('Content-Type: application/json');

try {
    // Validate input
    $required = ['register_no', 'year', 'semester', 'department', 'section', 'test_type', 'subject_code'];
    foreach ($required as $param) {
        if (!isset($_GET[$param])) {
            throw new Exception("Missing parameter: $param");
        }
    }

    // Get test_id
    $stmt = $mysqli->prepare("SELECT id FROM test_results 
        WHERE year = ? 
        AND semester = ? 
        AND department = ? 
        AND section = ? 
        AND test_type = ? 
        AND subject_code = ?");
    $stmt->bind_param("iissss", 
        $_GET['year'],
        $_GET['semester'],
        $_GET['department'],
        $_GET['section'],
        $_GET['test_type'],
        $_GET['subject_code']
    );
    $stmt->execute();
    $test = $stmt->get_result()->fetch_assoc();
    
    if (!$test) {
        throw new Exception("Test configuration not found");
    }

    // Get student marks
    $stmt = $mysqli->prepare("SELECT question_number, marks, attended 
        FROM student_marks 
        WHERE test_id = ? AND register_no = ?");
    $stmt->bind_param("is", $test['id'], $_GET['register_no']);
    $stmt->execute();
    $result = $stmt->get_result();

    $marks = [];
    $total_marks = 0;
    while ($row = $result->fetch_assoc()) {
        $marks[] = [
            'question_number' => $row['question_number'],
            'marks' => $row['marks'],
            'attended' => $row['attended']
        ];
        $total_marks += $row['marks'];
    }

    echo json_encode([
        'success' => true,
        'marks' => $marks,
        'total_marks' => $total_marks
    ]);

} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}
?>