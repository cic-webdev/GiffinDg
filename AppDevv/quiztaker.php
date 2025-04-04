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

if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty(file_get_contents("php://input"))) {
    $data = json_decode(file_get_contents("php://input"), true);
    $quiz_id = $data['quiz_id'];
    $user_answers = $data['answers'];
    $score = 0;
    $total_questions = 0;

    foreach ($user_answers as $question_id => $user_answer) {
        $question_query = "SELECT question_type FROM tblquestions WHERE question_id = $question_id";
        $question_result = $conn->query($question_query);
        $question_type = $question_result->fetch_assoc()['question_type'];

        if ($question_type == 'multiple-choice') {
            $correct_choice_query = "SELECT choice_text FROM tblchoices WHERE question_id = $question_id AND is_correct = 1";
            $correct_choice = $conn->query($correct_choice_query)->fetch_assoc()['choice_text'];
            if ($correct_choice === $user_answer) $score++;

        } elseif ($question_type == 'true-false') {
            $correct_tf_query = "SELECT correct_answer FROM tbltruefalse WHERE question_id = $question_id";
            $correct_tf = $conn->query($correct_tf_query)->fetch_assoc()['correct_answer'];
            if ($correct_tf === $user_answer) $score++;

        } else {
            $correct_answer_query = "SELECT correct_answer FROM tblidentification WHERE question_id = $question_id";
            $correct_answer = $conn->query($correct_answer_query)->fetch_assoc()['correct_answer'];
            if (strcasecmp($correct_answer, $user_answer) == 0) $score++;
        }

        $total_questions++;
    }

    $conn->close();

    echo json_encode([
        'score' => $score,
        'total' => $total_questions,
        'message' => $score == $total_questions ? "Perfect Score!" : "Keep practicing!"
    ]);
    exit;
}

$quiz_id = isset($_GET['quiz_id']) ? (int)$_GET['quiz_id'] : 1;
$quiz_query = "SELECT * FROM tblquiz WHERE quiz_id = $quiz_id";
$quiz_result = $conn->query($quiz_query);
$quiz_data = $quiz_result->fetch_assoc();
$questions_query = "SELECT * FROM tblquestions WHERE quiz_id = $quiz_id";
$questions_result = $conn->query($questions_query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quiz Taker</title>
    <style>
      body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }

        .navbar {
            background-color: rgba(128, 0, 0, 0.8); 
            width: 100%;
            height: 8vh; 
            display: flex;
            justify-content: space-between;
            align-items: center; 
            border-bottom: 3px solid rgb(210, 210, 71);
            padding: 0 20px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            box-sizing: border-box; 
        }

        .navbar .logo {
            font-size: 24px;
            font-weight: bold;
            color: white;
            text-decoration: none;
            margin-right: 20px; 
        }

        .navbar-links {
            display: flex;
            align-items: center; 
        }

        .navbar a {
            color: white;
            text-align: center;
            padding: 14px 20px;
            text-decoration: none;
            font-size: 18px;
        }

        .menu-icon {
            display: none;
            padding: 14px 20px;
            color: white;
            font-size: 20px;
            cursor: pointer;
        }

        .navbar a:hover {
            background-color: rgb(210, 210, 71);
            color: white;
        }

        @media screen and (max-width: 600px) {
            .navbar a {
                display: none;
            }

            .menu-icon {
                display: block;
            }

            .navbar.responsive {
                position: relative;
            }

            .navbar.responsive .navbar-links {
                display: flex;
                flex-direction: column;
                width: 100%;
                background-color: maroon;
                position: absolute;
                top: 8vh;
                left: 0;
            }

            .navbar.responsive a {
                display: block;
                padding: 14px;
                text-align: center;
            }
        }

        .container {
            padding: 20px;
        }

        .question {
            background-color: #fff;
            border: 1px solid #ddd;
            border-radius: 5px;
            padding: 15px;
            margin-bottom: 10px;
        }

        .finish-button {
            position: fixed;
            bottom: 20px;
            right: 20px;
            padding: 10px 15px;
            margin-top: 10px;
            background-color: maroon;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .finish-button:hover {
            background-color: rgb(210, 210, 71);
        }
    </style>
</head>
<body>
<div class="navbar" id="navbar">
        <span class="menu-icon" onclick="toggleMenu()">&#9776;</span>
        <div class="navbar-links">
            <a href="studyrev.php" class="logo">Study Buddy</a>
        </div>
        <a href="profile.php" class="logout"><?php echo htmlspecialchars($_SESSION['USERNAME']); ?></a>
    </div>

    <div class="container">
        <h1>Quiz: <?php echo htmlspecialchars($quiz_data['quiz_name']); ?></h1>

        <?php
        while ($question = $questions_result->fetch_assoc()) {
            echo '<div class="question">';
            echo '<p><strong>' . ucfirst($question['question_type']) . ' Question:</strong> ' . htmlspecialchars($question['question_text']) . '</p>';

            if ($question['question_type'] == 'multiple-choice') {
                $choices_query = "SELECT * FROM tblchoices WHERE question_id = " . $question['question_id'];
                $choices_result = $conn->query($choices_query);

                while ($choice = $choices_result->fetch_assoc()) {
                    echo '<label><input type="radio" name="question_' . $question['question_id'] . '" value="' . htmlspecialchars($choice['choice_text']) . '" onclick="recordAnswer(' . $question['question_id'] . ', \'' . htmlspecialchars($choice['choice_text']) . '\')">' . htmlspecialchars($choice['choice_text']) . '</label><br>';
                }
            } elseif ($question['question_type'] == 'true-false') {
                echo '<label><input type="radio" name="question_' . $question['question_id'] . '" value="True" onclick="recordAnswer(' . $question['question_id'] . ', \'True\')"> True</label><br>';
                echo '<label><input type="radio" name="question_' . $question['question_id'] . '" value="False" onclick="recordAnswer(' . $question['question_id'] . ', \'False\')"> False</label><br>';
            } else {
                echo '<input type="text" name="question_' . $question['question_id'] . '" placeholder="Your answer here" oninput="recordAnswer(' . $question['question_id'] . ', this.value)" /><br>';
            }

            echo '</div>';
        }
        ?>

        <button class="finish-button" onclick="finishQuiz()">Finish</button>
    </div>

    <script>
    let userAnswers = {};

    function recordAnswer(questionId, answer) {
        userAnswers[questionId] = answer;
    }

    function finishQuiz() {
    fetch('', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ quiz_id: <?php echo $quiz_id; ?>, answers: userAnswers })
    })
    .then(response => response.json())
    .then(data => {
        alert("Your Score: " + data.score + "/" + data.total + "\n" + data.message);
        window.location.href = 'quizselector.php';
    })
    .catch(error => console.error('Error:', error));
}

    </script>
</body>
</html>

<?php
$conn->close();
?>