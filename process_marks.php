<?php
// Assuming you have the database connection set up
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the marks_entry_id (ensure this is passed or available in session)
    $marks_entry_id = $_POST['marks_entry_id'];

    // Collect course outcomes data
    $courseOutcomes = $_POST['course_outcome'];
    $success = true;

    // Insert each course outcome into the database
    foreach ($courseOutcomes as $courseOutcome) {
        $stmt = $conn->prepare("INSERT INTO course_outcomes (marks_entry_id, course_outcome) VALUES (?, ?)");
        $stmt->bind_param("is", $marks_entry_id, $courseOutcome);

        if (!$stmt->execute()) {
            $success = false;
            break;
        }
    }

    // Respond with success or failure
    if ($success) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false]);
    }
}
?>
