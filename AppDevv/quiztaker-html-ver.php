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
            <a href="study student.php" class="logo">Study Buddy</a>
        </div>
        <a href="profile.php" class="logout"><?php echo htmlspecialchars($_SESSION['USERNAME']); ?></a>
    </div>

    <div class="container">
        <h1>Quiz Taker</h1>

        <div class="question">
            <p><strong>Identification Question:</strong> What is the capital of France?</p>
            <input type="text" placeholder="Your answer here" />
        </div>

        <div class="question">
            <p><strong>Multiple Choice Question:</strong> Which of the following is a fruit?</p>
            <label><input type="radio" name="fruit" value="apple"> Apple</label><br>
            <label><input type="radio" name="fruit" value="carrot"> Carrot</label><br>
            <label><input type="radio" name="fruit" value="broccoli"> Broccoli</label><br>
            <label><input type="radio" name="fruit" value="banana"> Banana</label><br>
        </div>

        <div class="question">
            <p><strong>True/False Question:</strong> The Earth is flat.</p>
            <label><input type="radio" name="earthFlat" value="true"> True</label><br>
            <label><input type="radio" name="earthFlat" value="false"> False</label><br>
        </div>

        <button class="finish-button" onclick="finishQuiz()">Finish</button>
    </div>

    <script>
        function finishQuiz() {
        }
    </script>

</body>
</html>