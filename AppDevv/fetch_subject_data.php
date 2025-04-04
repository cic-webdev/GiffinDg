<?php
include 'database.php';

$sql = "SELECT * FROM tblSubject";  
$stmt = $conn->query($sql);

$subjects = [];
if ($stmt !== false) {
    while ($row = $stmt->fetch_assoc()) {
        $subjectId = $row['SubjectID'];
        $row['Units'] = fetchUnits($conn, $subjectId);
        $row['Reviewers'] = fetchReviewers($conn, $subjectId);
        $row['Quizzes'] = fetchQuizzes($conn, $subjectId);
        $row['Reminders'] = fetchReminders($conn, $subjectId);
        $subjects[] = $row;
    }
}

$conn->close();

header('Content-Type: application/json');
echo json_encode($subjects);

function fetchUnits($conn, $subjectId) {
    $units = [];
    $sql = "SELECT * FROM tblUnit WHERE SubjectID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $subjectId); 
    $stmt->execute(); 
    $result = $stmt->get_result(); 

    while ($row = $result->fetch_assoc()) { 
        $units[] = $row['UnitTitle'];
    }

    $stmt->close();
    return $units;
}

function fetchReviewers($conn, $subjectId) {
    $reviewers = [];
    $sql = "SELECT * FROM tblReviewer WHERE SubjectID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $subjectId);
    $stmt->execute();
    $result = $stmt->get_result();

    while ($row = $result->fetch_assoc()) {
        $reviewers[] = $row['ReviewerTitle'];
    }

    $stmt->close();
    return $reviewers;
}

function fetchQuizzes($conn, $subjectId) {
    $quizzes = [];
    $sql = "SELECT * FROM tblQuiz WHERE SubjectID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $subjectId);
    $stmt->execute();
    $result = $stmt->get_result();

    while ($row = $result->fetch_assoc()) {
        $quizzes[] = $row['QuizTitle'];
    }

    $stmt->close();
    return $quizzes;
}

function fetchReminders($conn, $subjectId) {
    $reminders = [];
    $sql = "SELECT * FROM tblTask WHERE SubjectID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $subjectId);
    $stmt->execute();
    $result = $stmt->get_result();

    while ($row = $result->fetch_assoc()) {
        $reminders[] = $row['ReminderText'];
    }

    $stmt->close();
    return $reminders;
}
?>