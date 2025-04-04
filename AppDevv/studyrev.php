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
    <title>Study Buddy</title>
    <style>
       body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: lightgray;
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
            width: 45%;
            height: 180px;
            margin: 10px;
            display: flex;
            justify-content: center;
            align-items: center;
            font-size: 40px;
            color: maroon;
            text-align: center;
            text-decoration: none;
            transition: transform 0.3s ease;
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
    </style>
</head>
<body>

    <div class="navbar" id="navbar">
        <span class="menu-icon" onclick="toggleMenu()">&#9776;</span>
        <div class="navbar-links">
            <a href="study student.php" class="logo">Study Buddy</a>
        </div>
        <a href="profile.php" class="logout"><?php echo htmlspecialchars($_SESSION['USERNAME']); ?></a>
    </div>

    <div class="main-content">
        <a href="ppts1 copy.php" class="box">Units</a>
        <a href="Reviewer.php" class="box">Reviewer</a>
        <a href="quizselector.php" class="box">My Quizzes</a>
        <a href="Reminder copy.php" class="box">Reminders</a>
    </div>

    <script>
    document.addEventListener("DOMContentLoaded", function() {
        fetchSubjectData();
    });

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
        const mainContent = document.querySelector('.main-content');
        mainContent.innerHTML = '';

        subjects.forEach(subject => {
            const subjectBox = document.createElement('div');
            subjectBox.className = 'box';
            subjectBox.innerText = subject.SubjectTitle;

            subjectBox.innerHTML += '<h4>Units:</h4>' + subject.Units.map(unit => `<p>${unit}</p>`).join('');
            subjectBox.innerHTML += '<h4>Reviewers:</h4>' + subject.Reviewers.map(reviewer => `<p>${reviewer}</p>`).join('');
            subjectBox.innerHTML += '<h4>Quizzes:</h4>' + subject.Quizzes.map(quiz => `<p>${quiz}</p>`).join('');
            subjectBox.innerHTML += '<h4>Reminders:</h4>' + subject.Reminders.map(reminder => `<p>${reminder}</p>`).join('');

            mainContent.appendChild(subjectBox);
        });
    }
</script>

</body>
</html>
