<?php
session_start();
if (!isset($_SESSION['USERNAME'])) {
    header("Location: login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Task Manager</title>
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

            .navbar.responsive .navbar-links,
            .navbar.responsive .navbar-right {
                display: flex;
                flex-direction: column;
                width: 100%;
                background-color: rgba(128, 0, 0, 0.8);
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

        .task-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            padding: 20px;
            margin-top: 20px;
        }

        .task-card {
            background-color: white;
            padding: 10px;
            border-radius: 10px;
            box-shadow: 0px 4px 8px grey;
            position: relative;
        }

        .task-card:hover {
            box-shadow: 0px 8px 16px rgba(0, 0, 0, 0.2);
        }

        .three-dots {
            position: absolute;
            top: 10px;
            right: 10px;
            cursor: pointer;
            font-size: 18px;
        }

        .dropdown-menu {
            display: none;
            position: absolute;
            top: 30px;
            right: 10px;
            background-color: white;
            border: 1px solid #ccc;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            padding: 10px;
            z-index: 1;
        }

        .dropdown-menu button {
            background-color: transparent;
            border: none;
            cursor: pointer;
            color: maroon;
            font-size: 16px;
            padding: 10px;
            display: block;
            text-align: left;
        }

        .dropdown-menu button:hover {
            background-color: rgb(224, 208, 64);
        }

        .add-task-btn {
            width: 50px;
            height: 50px;
            background-color: maroon;
            color: white;
            border: none;
            border-radius: 50%;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            position: fixed;
            bottom: 20px;
            right: 10%;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.2);
            transition: background-color 0.3s;
            margin-bottom: 20px;
        }

        .add-task-btn:hover {
            background-color: rgb(210, 210, 71);
        }

        .add-task-btn:before {
            content: "+";
            font-size: 24px;
        }

        .modal {
            display: none;
            position: fixed;
            z-index: 1;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0,0,0,0.4);
        }

        .modal-content {
            background-color: #fefefe;
            margin: 15% auto;
            padding: 40px;
            border: 12px solid #888;
            width: 300px;
            border-radius: 8px;
            background-color: white;
        }

        .modal input {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        .modal button {
            background-color: maroon;
            color: BLACK;
            border: none;
            padding: 10px;
            border-radius: 4px;
            cursor: pointer;
        }

        .modal button:hover {
            background-color: rgb(224, 208, 64);
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

<div class="task-container" id="task-container" display="hidden">
    <?php
    $serverName = "localhost";
    $username = "root";
    $password = "";
    $database = "dbStuddyBuddy";

    $conn = new mysqli($serverName, $username, $password, $database);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $sql = "SELECT * FROM tasks";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo "<div class='task-card' id='task_" . htmlspecialchars($row['id']) . "'>";
            echo "<h2>" . htmlspecialchars($row['task_name']) . "</h2>";
            echo "<p>Due Date: " . htmlspecialchars($row['due_date']) . "</p>";
            echo "<p>Time: " . htmlspecialchars($row['task_time']) . "</p>";
            echo "<div class='three-dots' onclick='toggleDropdown(this)'>⋮</div>";
            echo "<div class='dropdown-menu'>";
            echo "<button onclick='editTaskCard(" . intval($row['id']) . ", \"" . htmlspecialchars($row['task_name']) . "\", \"" . htmlspecialchars($row['due_date']) . "\", \"" . htmlspecialchars($row['task_time']) . "\")'>Edit</button>";
            echo "<button onclick='deleteTaskCard(" . intval($row['id']) . ")'>Delete</button>";
            echo "</div>";
            echo "</div>";
        }
    } else {
        echo "<p>No tasks found.</p>";
    }

    $conn->close();
    ?>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        const dueDateInput = document.getElementById('dueDate');
        const today = new Date().toISOString().split('T')[0];
        dueDateInput.setAttribute('min', today);
    });

    function toggleDropdown(element) {
        let dropdownMenu = element.nextElementSibling;
        dropdownMenu.style.display = dropdownMenu.style.display === "block" ? "none" : "block";
    }

    function deleteTaskCard(taskId) {
        if (confirm("Are you sure you want to delete this task?")) {
            const formData = new FormData();
            formData.append('task_id', taskId);
            fetch('delete_task.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.text())
            .then(data => {
                alert(data);
                document.getElementById('task_' + taskId).remove();
            })
            .catch(error => console.error('Error:', error));
        }
    }

    function editTaskCard(taskId, taskName, dueDate, taskTime) {
        document.getElementById('taskName').value = taskName;
        document.getElementById('dueDate').value = dueDate;
        document.getElementById('taskTime').value = taskTime;

        const form = document.querySelector('#addTaskModal form');
        let input = document.createElement('input');
        input.type = 'hidden';
        input.name = 'task_id';
        input.value = taskId;
        form.appendChild(input);
        form.action = "edit_task.php";

        showModal();
    }

    function addTask(event) {
    event.preventDefault(); 

    const taskTimeInput = document.getElementById('taskTime');
    const dueDateInput = document.getElementById('dueDate');

    const now = new Date();
    const currentTime = `${String(now.getHours()).padStart(2, '0')}:${String(now.getMinutes()).padStart(2, '0')}`;
    
    const selectedTime = taskTimeInput.value;
    const selectedDate = new Date(dueDateInput.value + 'T' + selectedTime);

    if (selectedDate < now) {
        alert("Please select a due date and time that is equal to or later than the current date and time.");
        return; 
    }

    const formData = new FormData(document.getElementById('addTaskForm'));

    fetch('add_task.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.text())
    .then(data => {
        alert(data);
        closeModal();
        location.reload();
    })
    .catch(error => console.error('Error:', error));
}
function fetchSubjectData() {
    fetch('fetch_subject_data.php')
        .then(response => {
            if (!response.ok) {
                throw new Error("Network response was not ok");
            }
            return response.json();
        })
        .then(data => {
            displaySubjects(data);
        })
        .catch(error => console.error("Fetch error:", error));
}

function displaySubjects(subjects) {
    const subjectContainer = document.getElementById('subject-container');
    subjectContainer.innerHTML = '';

    subjects.forEach(subject => {
        const subjectBox = document.createElement('div');
        subjectBox.className = 'task-card';
        subjectBox.innerHTML = `<h2>${subject.SubjectTitle}</h2>`;

        subjectBox.innerHTML += '<h4>Units:</h4>' + subject.Units.map(unit => `<p>${unit}</p>`).join('');
        subjectBox.innerHTML += '<h4>Reviewers:</h4>' + subject.Reviewers.map(reviewer => `<p>${reviewer}</p>`).join('');
        subjectBox.innerHTML += '<h4>Quizzes:</h4>' + subject.Quizzes.map(quiz => `<p>${quiz}</p>`).join('');
        subjectBox.innerHTML += '<h4>Reminders:</h4>' + subject.Reminders.map(reminder => `<p>${reminder}</p>`).join('');

        subjectContainer.appendChild(subjectBox);
    });
}
</script>

</body>
</html>