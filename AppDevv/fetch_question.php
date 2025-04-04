<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "dbstuddybuddy";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (isset($_GET['question_id'])) {
    $question_id = (int)$_GET['question_id'];
    $result = $conn->query("SELECT * FROM tblquestions WHERE question_id = $question_id");
    $question = $result->fetch_assoc();

    if ($question['question_type'] == 'multiple-choice') {
        $choicesResult = $conn->query("SELECT choice_text, is_correct FROM tblchoices WHERE question_id = $question_id");
        $question['choices'] = $choicesResult->fetch_all(MYSQLI_ASSOC);
    } elseif ($question['question_type'] == 'true-false') {
        $tfResult = $conn->query("SELECT correct_answer FROM tbltruefalse WHERE question_id = $question_id");
        $question['correct_answer'] = $tfResult->fetch_assoc()['correct_answer'];
    } elseif ($question['question_type'] == 'identification') {
        $idResult = $conn->query("SELECT correct_answer FROM tblidentification WHERE question_id = $question_id");
        $question['correct_answer'] = $idResult->fetch_assoc()['correct_answer'];
    }

    echo json_encode($question);
}

$conn->close();
?>
