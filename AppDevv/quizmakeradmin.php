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

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['create_question'])) {
    $quiz_id = $_GET['quiz_id'];
    $question_text = $_POST['question_text'];
    $question_type = $_POST['question_type'];

    $stmt = $conn->prepare("INSERT INTO tblquestions (quiz_id, question_text, question_type) VALUES (?, ?, ?)");
    $stmt->bind_param("iss", $quiz_id, $question_text, $question_type);
    $stmt->execute();
    $question_id = $stmt->insert_id;
    $stmt->close();

    if ($question_type == 'multiple-choice') {
        for ($i = 1; $i <= 4; $i++) {
            if (!empty($_POST["choice_text_$i"])) {
                $choice_text = $_POST["choice_text_$i"];
                $is_correct = ($_POST['correct_choice'] == $i) ? 1 : 0;

                $stmt = $conn->prepare("INSERT INTO tblchoices (question_id, choice_text, is_correct) VALUES (?, ?, ?)");
                $stmt->bind_param("isi", $question_id, $choice_text, $is_correct);
                $stmt->execute();
            }
        }
    } elseif ($question_type == 'true-false') {
        $correct_answer = $_POST['correct_answer'];
        $stmt = $conn->prepare("INSERT INTO tbltruefalse (question_id, correct_answer) VALUES (?, ?)");
        $stmt->bind_param("is", $question_id, $correct_answer);
        $stmt->execute();
    } elseif ($question_type == 'identification') {
        $correct_answer = $_POST['identification_answer'];
        $stmt = $conn->prepare("INSERT INTO tblidentification (question_id, correct_answer) VALUES (?, ?)");
        $stmt->bind_param("is", $question_id, $correct_answer);
        $stmt->execute();
    }

    header("Location: quizmakeradmin.php?quiz_id=" . $quiz_id);
    exit();
}

$sql = "SELECT quiz_id, quiz_name FROM tblquiz";
$quiz_data = $conn->query($sql)->fetch_all(MYSQLI_ASSOC);

$questions = [];
if (isset($_GET['quiz_id'])) {
    $quiz_id = $_GET['quiz_id'];
    $questions = $conn->query("SELECT * FROM tblquestions WHERE quiz_id = $quiz_id")->fetch_all(MYSQLI_ASSOC);
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Quiz Maker</title>
</head>
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

        .container {
            padding: 20px;
            margin: 20px auto;
            max-width: 1200px;
        }

        .box {
            background-color: white;
            border: 2px solid maroon;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        h1 {
            text-align: center;
            color: maroon;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group input, .form-group select {
            width: 100%;
            padding: 10px;
            margin-top: 5px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }

        button {
            background-color: maroon;
            color: white;
            padding: 10px 15px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        button:hover {
            background-color: rgb(210, 210, 71);
        }

        .finish-button {
            position: fixed;
            bottom: 20px;
            right: 20px;
        }
        #edit-overlay {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.5); 
    display: none; 
    z-index: 1000; 
    justify-content: center;
    align-items: center;
    flex-direction: column;
    overflow: auto;
}

.overlay-content {
    background-color: white;
    padding: 20px;
    border-radius: 8px;
    max-width: 600px;
    width: 80%;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    position: relative;
}

.overlay-content h2 {
    margin-bottom: 20px;
}

button {
    margin-top: 10px;
}

button:hover {
    background-color: rgb(210, 210, 71);
}

#edit-overlay .overlay-content button {
    display: inline-block;
}
.finish-button {
    position: fixed;
    bottom: 20px;
    right: 20px;
    background-color: rgba(128, 0, 0, 0.8);
    color: white;
    padding: 15px 30px;
    font-size: 16px;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
    transition: background-color 0.3s ease;
}

.finish-button:hover {
    background-color: gold;
}

.hidden {
    display:none;
}

.question_text {
    width: 100%;
    box-sizing: border-box; 
    padding: 8px;
    font-size: 16px;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
    border: 1px solid #ccc;
    border-radius: 4px;
}
</style>
<body>
    <div class="navbar" id="navbar">
        <span class="menu-icon" onclick="toggleMenu()">&#9776;</span>
        <div class="navbar-links">
            <a href="studyrev admin.php" class="logo">Study Buddy</a>
        </div>
        <a href="profile.php" class="logout"><?php echo htmlspecialchars($_SESSION['USERNAME']); ?></a>
    </div>
<div class="container">
<div class="container">
    
    <h1>Quiz Maker</h1>

<div class="box">
    <h2>Create a Question</h2>
    <form method="POST" action="quizmakeradmin.php?quiz_id=<?php echo $quiz_id; ?>">
    <div class="form-group">
        <label>Question Text:</label>
        <input type="text" name="question_text" class="question_text" required>
    </div>

    <div class="form-group">
        <label>Question Type:</label>
        <select name="question_type" id="question_type" onchange="toggleQuestionFields()" required>
            <option disabled selected hidden>Please Select</option>
            <option value="identification">Identification</option>
            <option value="multiple-choice">Multiple Choice</option>
            <option value="true-false">True/False</option>
        </select>
    </div>

    <div id="identification" style="display:none;">
        <label>Identification Answer:</label>
        <input type="text" name="identification_answer" id="identification_answer">
    </div>

    <div id="multiple-choice" style="display:none;">
        <label>Choices:</label>
        <?php for ($i = 1; $i <= 4; $i++): ?>
            <div>
                <input type="text" name="choice_text_<?php echo $i; ?>" id="choice_text_<?php echo $i; ?>" placeholder="Choice <?php echo $i; ?>" class="choice-input">
            </div>
        <?php endfor; ?>
        <label>Correct Choice:</label>
        <select name="correct_choice" id="correct_choice">
            <option value="1">Choice 1</option>
            <option value="2">Choice 2</option>
            <option value="3">Choice 3</option>
            <option value="4">Choice 4</option>
        </select>
    </div>

    <div id="true-false" style="display:none;">
        <label>Correct Answer:</label>
        <select name="correct_answer" id="correct_answer">
            <option value="True">True</option>
            <option value="False">False</option>
        </select>
    </div>

    <button type="submit" name="create_question">Create Question</button>
</form>
</div>

<script>
    function toggleQuestionFields() {
        document.getElementById('identification').style.display = 'none';
        document.getElementById('multiple-choice').style.display = 'none';
        document.getElementById('true-false').style.display = 'none';

        const questionType = document.getElementById('question_type').value;
        if (questionType === 'identification') {
            document.getElementById('identification').style.display = 'block';
        } else if (questionType === 'multiple-choice') {
            document.getElementById('multiple-choice').style.display = 'block';
        } else if (questionType === 'true-false') {
            document.getElementById('true-false').style.display = 'block';
        }
    }

    function toggleQuestionFields() {
        // Get the selected question type
        var selectedType = document.getElementById('question_type').value;

        // Hide all sections initially
        document.getElementById('identification').style.display = 'none';
        document.getElementById('multiple-choice').style.display = 'none';
        document.getElementById('true-false').style.display = 'none';

        // Reset required attributes for all inputs
        var choiceInputs = document.querySelectorAll('.choice-input');
        choiceInputs.forEach(function(input) {
            input.removeAttribute('required');
        });
        document.querySelector('select[name="correct_choice"]').removeAttribute('required');
        document.querySelector('select[name="correct_answer"]').removeAttribute('required');
        document.querySelector('input[name="identification_answer"]').removeAttribute('required');
        
        // Show the relevant section and set the required attribute dynamically
        if (selectedType === 'identification') {
            document.getElementById('identification').style.display = 'block';
            document.querySelector('input[name="identification_answer"]').setAttribute('required', 'true');
        } else if (selectedType === 'multiple-choice') {
            document.getElementById('multiple-choice').style.display = 'block';
            choiceInputs.forEach(function(input) {
                input.setAttribute('required', 'true');
            });
            document.querySelector('select[name="correct_choice"]').setAttribute('required', 'true');
        } else if (selectedType === 'true-false') {
            document.getElementById('true-false').style.display = 'block';
            document.querySelector('select[name="correct_answer"]').setAttribute('required', 'true');
        }
    }
</script>



    <div class="box">
        <h2>Created Questions</h2>
        <?php foreach ($questions as $question): ?>
            <div id="question-<?php echo $question['question_id']; ?>">
                <p><?php echo htmlspecialchars($question['question_text']); ?></p>

                <!-- Display answers based on question type -->
                <?php if ($question['question_type'] == 'multiple-choice'): ?>
                    <?php
                    // Fetch choices for multiple-choice questions
                    $choices = $conn->query("SELECT choice_text, is_correct FROM tblchoices WHERE question_id = " . $question['question_id'])->fetch_all(MYSQLI_ASSOC);
                    ?>
                    <ul>
                        <?php foreach ($choices as $choice): ?>
                            <li><?php echo htmlspecialchars($choice['choice_text']); ?><?php echo $choice['is_correct'] ? " (Correct)" : ""; ?></li>
                        <?php endforeach; ?>
                    </ul>
                <?php elseif ($question['question_type'] == 'true-false'): ?>
                    <?php
                    // Fetch correct answer for true/false question
                    $tf_answer = $conn->query("SELECT correct_answer FROM tbltruefalse WHERE question_id = " . $question['question_id'])->fetch_assoc();
                    ?>
                    <p>Correct Answer: <?php echo htmlspecialchars($tf_answer['correct_answer']); ?></p>
                <?php elseif ($question['question_type'] == 'identification'): ?>
                    <?php
                    // Fetch correct answer for identification question
                    $id_answer = $conn->query("SELECT correct_answer FROM tblidentification WHERE question_id = " . $question['question_id'])->fetch_assoc();
                    ?>
                    <p>Answer: <?php echo htmlspecialchars($id_answer['correct_answer']); ?></p>
                <?php endif; ?>

                <button onclick="deleteQuestion(<?php echo $question['question_id']; ?>)">Delete</button>



<script>
function deleteQuestion(questionId) {
    if (confirm("Are you sure you want to delete this question?")) {
        fetch('delete_question.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded'
            },
            body: new URLSearchParams({ question_id: questionId })
        })
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                alert("Question deleted successfully.");
                // Optionally, remove the question from the page without reloading
                document.getElementById(`question-${questionId}`).remove();
            } else {
                alert("Error: " + data.message);
            }
        })
        .catch(error => {
            alert("An error occurred: " + error);
        });
    }
}
</script>
<!-- Edit Question Overlay -->
<div id="edit-overlay">
    <div class="overlay-content">
        <h2>Edit Question</h2>
        <form id="edit-form">
            <input type="hidden" name="question_id" id="edit-question-id">

            

            <div class="form-group">
                <label>Question Text:</label>
                <input type="text" name="question_text" id="edit-question-text" required>
            
                <select name="question_type" id="edit-question-type" onchange="toggleEditQuestionFields()" required>
                    <option value="identification">Identification</option>
                    <option value="multiple-choice">Multiple Choice</option>
                    <option value="true-false">True/False</option>
                </select>
            </div>

            <div id="edit-identification" style="display: none;">
                <label>Identification Answer:</label>
                <input type="text" name="identification_answer" id="edit-identification-answer">
            </div>

            <div id="edit-multiple-choice" style="display: none;">
                <label>Choices:</label>
                <div id="edit-choices-container"></div>
            </div>

            <div id="edit-true-false" style="display: none;">
                <label>Correct Answer:</label>
                <input type="radio" name="correct_answer" value="True" id="edit-true"> True
                <input type="radio" name="correct_answer" value="False" id="edit-false"> False
            </div>

            <button type="button" onclick="saveEditedQuestion()">Save Changes</button>
            <button type="button" onclick="closeEditOverlay()">Cancel</button>
        </div>
        </form>
    </div>


<script>
    function editQuestion(questionId) {
    fetch(`fetch_question.php?question_id=${questionId}`)
        .then(response => response.json())
        .then(data => {
            // Populate the form with question data
            document.getElementById('edit-question-id').value = data.question_id;
            document.getElementById('edit-question-text').value = data.question_text;
            document.getElementById('edit-question-type').value = data.question_type;

            toggleEditQuestionFields();
            if (data.question_type === 'multiple-choice') {
                document.getElementById('edit-choices-container').innerHTML = data.choices.map((choice, index) => `
                    <div>
                        <input type="text" name="choice_text_${index + 1}" value="${choice.choice_text}">
                        <input type="radio" name="correct_choice" value="${index + 1}" ${choice.is_correct ? 'checked' : ''}> Correct
                    </div>
                `).join('');
            } else if (data.question_type === 'true-false') {
                document.getElementById(data.correct_answer === 'True' ? 'edit-true' : 'edit-false').checked = true;
            } else if (data.question_type === 'identification') {
                document.getElementById('edit-identification-answer').value = data.correct_answer;
            }

            document.getElementById('edit-overlay').style.display = 'flex'; // Show overlay
        });
}

function closeEditOverlay() {
    document.getElementById('edit-overlay').style.display = 'none'; // Close overlay
}
function saveEditedQuestion() {
    // Save the current scroll position to localStorage
    localStorage.setItem('scrollPosition', window.scrollY);

    const formData = new FormData(document.getElementById('edit-form'));

    fetch('update_question.php', {
        method: 'POST',
        body: formData
    }).then(response => response.json()).then(data => {
        if (data.status === 'success') {
            alert("Question updated successfully.");
            closeEditOverlay();

            // Reload the page to reflect the changes
            location.reload();  // This will reload the page
        } else {
            alert("Failed to update question: " + data.message);
        }
    }).catch(error => {
        alert("Error: " + error);
    });
}

// When the page loads, check for the stored scroll position in localStorage
window.onload = function() {
    const savedScrollPosition = localStorage.getItem('scrollPosition');
    if (savedScrollPosition !== null) {
        window.scrollTo(0, savedScrollPosition);  // Restore the scroll position
        localStorage.removeItem('scrollPosition');  // Remove it after use
    }
}
</script>
            </div>
        <?php endforeach; ?>
    </div>
</div>
<button id="finish-button" class="finish-button" onclick="window.location.href='quizselectoradmin.php'">Finish</button>
</div>
</body>
</html>

<?php $conn->close(); ?>
