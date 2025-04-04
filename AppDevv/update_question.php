<?php
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
    $question_text = $conn->real_escape_string($_POST['question_text']);
    $question_type = $_POST['question_type'];

    $conn->query("UPDATE tblquestions SET question_text = '$question_text', question_type = '$question_type' WHERE question_id = $question_id");

    if ($question_type == 'multiple-choice') {
        $conn->query("DELETE FROM tblchoices WHERE question_id = $question_id");
        for ($i = 1; $i <= 4; $i++) {
            $choice_text = $conn->real_escape_string($_POST["choice_text_$i"]);
            $is_correct = (isset($_POST['correct_choice']) && $_POST['correct_choice'] == $i) ? 1 : 0;
            $conn->query("INSERT INTO tblchoices (question_id, choice_text, is_correct) VALUES ($question_id, '$choice_text', $is_correct)");
        }
    } elseif ($question_type == 'true-false') {
        $correct_answer = $_POST['correct_answer'];
        $conn->query("UPDATE tbltruefalse SET correct_answer = '$correct_answer' WHERE question_id = $question_id");
    } elseif ($question_type == 'identification') {
        $correct_answer = $conn->real_escape_string($_POST['identification_answer']);
        $conn->query("UPDATE tblidentification SET correct_answer = '$correct_answer' WHERE question_id = $question_id");
    }

    echo json_encode(['status' => 'success']);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request']);
}

$conn->close();
?>
