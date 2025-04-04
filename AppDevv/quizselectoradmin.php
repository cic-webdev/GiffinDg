<?php
session_start();
if (!isset($_SESSION['USERNAME'])) {
    header("Location: login.php");
    exit;
}

// Database connection
$servername = "localhost";
$username = "root";  // Replace with your DB username
$password = "";  // Replace with your DB password
$dbname = "dbstuddybuddy";  // Replace with your DB name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle quiz creation
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['create_quiz'])) {
    $quiz_name = $_POST['quiz_name'];

    // Insert the new quiz into the tblquiz table
    $sql = "INSERT INTO tblquiz (quiz_name, subject_id) VALUES ('$quiz_name', 1)";  // Adjust subject_id as needed


    if ($conn->query($sql) === TRUE) {
        echo "<script>alert('New quiz created successfully!'); window.location.href='quizselectoradmin.php';</script>";
    } else {
        echo "Error creating quiz: " . $conn->error;
    }
}

// Fetch quizzes from the database
$quiz_query = "SELECT * FROM tblquiz";
$quiz_result = $conn->query($quiz_query);

// Handle quiz title update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['quiz_id'], $_POST['quiz_name'])) {
    $quiz_id = (int)$_POST['quiz_id'];
    $quiz_name = $conn->real_escape_string($_POST['quiz_name']);

    $update_query = "UPDATE tblquiz SET quiz_name = '$quiz_name' WHERE quiz_id = $quiz_id";
    if ($conn->query($update_query)) {
        echo json_encode(['success' => true, 'message' => 'Quiz title updated successfully.']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to update quiz title.']);
    }
    exit;
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quiz Selector</title>
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

        .main-content {
            display: flex;
            flex-wrap: wrap;
            flex-grow: 1;
            justify-content: space-around;
            align-items: center;
            background-color: lightgray;
            padding: 20px;
        }

        .box {
    background-color: white;
    border: 2px solid maroon;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
    width: 30%;
    height: 180px; /* Adjusted height to make room for centered text */
    margin: 10px;
    display: flex;
    flex-direction: column; /* Align items vertically */
    justify-content: center; /* Center content vertically */
    align-items: center; /* Center content horizontally */
    font-size: 20px;
    color: maroon;
    text-align: center;
    text-decoration: none;
    transition: transform 0.3s ease;
    padding: 15px;
}

        .box:hover {
            background-color: rgb(210, 210, 71);
            transform: scale(1.05);
            color: white;
            
        }

        @media screen and (max-width: 600px) {
            .box {
                width: 90%;
            }
        }

        .create-quiz-container {
    position: fixed; /* Fix it to the bottom of the screen */
    bottom: 20px; /* Set a distance from the bottom */
    right: 20px; /* Set a distance from the right */
    z-index: 100; /* Ensure it's above other elements */
}

.create-quiz-container button {
    padding: 15px 25px;
    background-color: maroon;
    color: white;
    border: none;
    cursor: pointer;
    font-size: 18px;
    border-radius: 5px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.2);
}

.create-quiz-container button:hover {
    background-color: darkred;
}

        .create-quiz-form {
            display: none;
            text-align: center;
            margin-top: 30px;
        }

        .create-quiz-form input {
            padding: 10px;
            margin-right: 10px;
            font-size: 16px;
        }

        .create-quiz-form button {
            padding: 10px 20px;
            background-color: maroon;
            color: white;
            border: none;
            cursor: pointer;
            font-size: 16px;
        }

        .create-quiz-form button:hover {
            background-color: darkred;
        }
        .quiz-actions {
    display: flex;
    justify-content: space-between; 
    width: 100%;
    gap: 10px;
    margin-top: 10px; 
}

.edit-btn, .delete-btn {
    padding: 5px 6px; 
    background-color: #f0ad4e; 
    color: white;
    text-decoration: none;
    border-radius: 3px;
    font-size: 12px;
    width: 28%; 
    text-align: center; 
    display: inline-block;
}

.delete-btn {
    background-color: #d9534f;
}

.edit-btn:hover, .delete-btn:hover {
    background-color: maroon;
}
#editOverlay {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.6);
    display: flex;
    justify-content: center;
    align-items: center;
    z-index: 1000;
}

.overlay-content {
    background: white;
    padding: 20px;
    border-radius: 8px;
    text-align: center;
    box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.3);
    width: 300px;
}

.overlay-content input {
    width: 100%;
    padding: 10px;
    margin-bottom: 10px;
    font-size: 16px;
}
.quiz-link {
    color: maroon;
    font-size: 20px;
    text-decoration: none;
    margin-bottom: 8px;
}

.quiz-link:hover {
    text-decoration: underline;
    color: darkred;
}
.create-btn {
    padding: 5px 6px;
    background-color: #5bc0de; 
    color: white;
    text-decoration: none;
    border-radius: 3px;
    font-size: 12px;
    width: 28%;
    text-align: center;
    display: inline-block;
}

.create-btn:hover {
    background-color: #31b0d5; 
}

.overlay-content input#newQuizTitle {
    width: calc(100% - 20px); 
    padding: 10px; 
    margin-bottom: 10px; 
    font-size: 16px; 
    border: 1px solid #ccc; 
    border-radius: 4px; 
    box-sizing: border-box; 
}

    </style>
</head>
<body>
    <div class="navbar" id="navbar">
        <span class="menu-icon" onclick="toggleMenu()">&#9776;</span>
        <div class="navbar-links">
            <a href="studyrev admin.php" class="logo">Study Buddy</a>
        </div>
        <a href="profile.php" class="logout"><?php echo htmlspecialchars($_SESSION['USERNAME']); ?></a>
    </div>

    <div class="create-quiz-container">
        <button onclick="toggleQuizForm()">Create New Quiz</button>
    </div>

    <div class="create-quiz-form" id="create-quiz-form">
        <h2>Create a New Quiz</h2>
        <form method="POST" action="quizselectoradmin.php">
            <input type="text" name="quiz_name" placeholder="Enter Quiz Name" required>
            <button type="submit" name="create_quiz">Create Quiz</button>
        </form>
    </div>

    
    <div class="main-content">
    <?php
    // Loop through each quiz and display it
    while ($quiz = $quiz_result->fetch_assoc()) {
        $quiz_name = htmlspecialchars($quiz['quiz_name']);
        $quiz_id = $quiz['quiz_id'];
        echo "<div class='box'>
                <span id='quiz-title-$quiz_id' class='quiz-link'>$quiz_name</span>
                <div class='quiz-actions'>
                    <button class='edit-btn' onclick='editQuizTitle($quiz_id)'>Edit</button>
                    <a href='quizmakeradmin.php?quiz_id=$quiz_id' class='create-btn'>Create Questions</a>
                    <a href='deletequiz.php?quiz_id=$quiz_id' class='delete-btn' onclick='return confirm(\"Are you sure you want to delete this quiz?\")'>Delete</a>
                </div>
              </div>";
    }
    ?>
</div>


    <script>
        function toggleQuizForm() {
    const form = document.getElementById("create-quiz-form");
    form.style.display = form.style.display === "none" ? "block" : "none";
}

        // Function to toggle between viewing and editing the quiz title
        function editQuizTitle(quizId) {
            document.getElementById(`quiz-title-${quizId}`).style.display = 'none';
            document.getElementById(`edit-title-${quizId}`).style.display = 'inline-block';
            document.getElementById(`edit-title-${quizId}`).focus();
        }

        // Function to save the new title and update it in the database
        function saveQuizTitle(quizId) {
            const newTitle = document.getElementById(`edit-title-${quizId}`).value;
            document.getElementById(`quiz-title-${quizId}`).textContent = newTitle;
            document.getElementById(`quiz-title-${quizId}`).style.display = 'inline';
            document.getElementById(`edit-title-${quizId}`).style.display = 'none';

            // AJAX request to update title in the database
            const xhr = new XMLHttpRequest();
            xhr.open("POST", "update_quiz_title.php", true);
            xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
            xhr.send(`quiz_id=${quizId}&quiz_name=${newTitle}`);
            xhr.onload = function () {
                if (xhr.status === 200) {
                    console.log("Quiz title updated successfully");
                } else {
                    console.error("Failed to update quiz title");
                }
            };
        }
    </script>
    <!-- Overlay for Editing Quiz Title -->
<div id="editOverlay" style="display: none;">
    <div class="overlay-content">
        <h2>Edit Quiz Title</h2>
        <input type="text" id="newQuizTitle" placeholder="Enter new quiz title" />
        <button onclick="saveNewTitle()">Save</button>
        <button onclick="closeOverlay()">Cancel</button>
    </div>
</div>
<script>
let currentQuizId = null;

function editQuizTitle(quizId) {
    currentQuizId = quizId;
    const currentTitle = document.getElementById(`quiz-title-${quizId}`).textContent;
    document.getElementById("newQuizTitle").value = currentTitle;
    document.getElementById("editOverlay").style.display = "flex";
}

function closeOverlay() {
    document.getElementById("editOverlay").style.display = "none";
    currentQuizId = null;
}

function saveNewTitle() {
    const newTitle = document.getElementById("newQuizTitle").value;
    if (newTitle && currentQuizId) {
        const xhr = new XMLHttpRequest();
        xhr.open("POST", "", true); // Send request to the same file
        xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
        xhr.onload = function () {
            if (xhr.status === 200) {
                const response = JSON.parse(xhr.responseText);
                if (response.success) {
                    document.getElementById(`quiz-title-${currentQuizId}`).textContent = newTitle;
                    closeOverlay();
                } else {
                    alert(response.message);
                }
            } else {
                alert("Failed to update quiz title.");
            }
        };
        xhr.send(`quiz_id=${currentQuizId}&quiz_name=${encodeURIComponent(newTitle)}`);
    }
}

function editQuizTitle(quizId) {
    currentQuizId = quizId;
    const currentTitle = document.getElementById(`quiz-title-${quizId}`).textContent;
    document.getElementById("newQuizTitle").value = currentTitle;
    document.getElementById("editOverlay").style.display = "flex";
}

</script>
</body>
</html>
<?php
// Close connection
$conn->close();
?>