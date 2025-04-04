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
            height: 180px;
            margin: 10px;
            display: flex;
            justify-content: center;
            align-items: center;
            font-size: 20px;
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
    <div class="subject-container" id="subject-container">

    <div class="main-content">
        <a href="quiztaker-html-ver.php" class="box">Test Quiz</a>
        <a href="quiztaker.php?quiz=quiz2" class="box">Test Quiz</a>
        <a href="quiztaker.php?quiz=quiz3" class="box">Test Quiz</a>
    </div>

    <script>
        function toggleMenu() {
            var navbar = document.getElementById("navbar");
            navbar.classList.toggle("responsive");
        }
    </script>

</body>
</html>