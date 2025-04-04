<?php
session_start();
if (!isset($_SESSION['USERNAME'])) {
    header("Location: login.php");
    exit;
}

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "dbstuddybuddy";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['question_id'])) {
    $question_id = (int)$_POST['question_id'];

    // First, get the question type
    $result = $conn->query("SELECT question_type FROM tblquestions WHERE question_id = $question_id");
    $question = $result->fetch_assoc();
    
    if ($question) {
        // Delete associated data based on question type
        switch ($question['question_type']) {
            case 'multiple-choice':
                $conn->query("DELETE FROM tblchoices WHERE question_id = $question_id");
                break;
            case 'true-false':
                $conn->query("DELETE FROM tbltruefalse WHERE question_id = $question_id");
                break;
            case 'identification':
                $conn->query("DELETE FROM tblidentification WHERE question_id = $question_id");
                break;
        }

        // Finally, delete the question itself
        $conn->query("DELETE FROM tblquestions WHERE question_id = $question_id");

        echo json_encode(['status' => 'success']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Question not found']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request']);
}

$conn->close();
?>
