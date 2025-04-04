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
                z-index: 10; 
            }

            .navbar.responsive a {
                display: block;
                padding: 14px;
                text-align: center;
            }
        }

        .subject-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            padding: 20px;
            margin-top: 20px;
            transition: margin-top 0.3s; 
        }

        .subject-container.shift-down {
            margin-top: 70px; 
        }

        .subject-card {
            background-color: #f4f4f4;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0px 4px 8px rgb(182, 181, 180);
            position: relative;
            transition: transform 0.2s; 
        }

        .subject-card:hover {
            box-shadow: 0px 8px 16px rgba(0, 0, 0, 0.2);
            transform: scale(1.02); 
        }

        .subject-card h2 {
            margin: 0;
            margin-bottom: 10px;
            font-size: 1.5em;
            color: #333;
        }

        .subject-card p {
            margin: 0;
            margin-bottom: 15px;
            color: #666;
        }

        .subject-card a {
            display: inline-block;
            padding: 10px 20px;
            background-color: rgba(128, 0, 0, 0.8);
            color: white;
            text-decoration: none;
            border-radius: 4px;
            transition: background-color 0.3s;
        }

        .subject-card a:hover {
            background-color: rgb(177, 177, 177);
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
            background-color: maroon; 
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
            border-radius: 4px;
            z-index: 15; 
            width: 120px;
        }

        .dropdown-menu button {
            width: 100%;
            padding: 10px;
            border: none;
            background-color: white;
            cursor: pointer;
            text-align: left;
            font-size: 14px;
        }

        .dropdown-menu button:hover {
            background-color: #ddd;
        }

        .add-subject-form {
            display: none; 
            background-color: #f4f4f4;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0px 4px 8px rgb(182, 181, 180);
            margin: 20px 0; 
        }

        .add-subject-form input {
            display: block;
            margin: 10px 0;
            padding: 10px;
            width: calc(100% - 22px);
        }

        .add-subject-form button {
            padding: 10px 20px;
            background-color: maroon;
            color: white;
            border: none;
            cursor: pointer;
            border-radius: 4px;
        }

        .add-subject-form button:hover {
            background-color: rgb(177, 177, 177);
        }

        .add-button {
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
            margin: 20px auto; 
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.2); 
            transition: background-color 0.3s; 
        }

        .add-button:hover {
            background-color: rgb(210, 210, 71); 
        }

        .add-button:before {
            content: "+"; 
            font-size: 24px; 
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
        <?php

            $serverName = "localhost";  
            $username = "root";
            $password = "";
            $dbName = "dbStuddyBuddy";
            $conn = new mysqli($serverName, $username, $password, $dbName);
            
            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }
            
            $sql = "SELECT * FROM tblSubject";
            $result = $conn->query($sql);
            
            if ($result->num_rows > 0) {

                while ($row = $result->fetch_assoc()) {
                    echo "<div class='subject-card'>";
                    echo "<h2>" . $row['SUBJECT_TITLE'] . "</h2>";
                    echo "<p>" . $row['SUB_DESC'] . "</p>";
                    echo "<a href='studyrev.php'>Go to class</a>";
                    echo "<div class='three-dots' onclick='toggleDropdown(this)'>⋮</div>";
                    echo "<div class='dropdown-menu'>";
                    echo "<button onclick='deleteSubjectCard(this, " . $row['SUB_ID'] . ")'>Delete</button>";
                    echo "</div>";
                    echo "</div>";
                }
            } else {
                echo "No subjects found.";
            }
            
            $conn->close();
        ?>
    </div>

    <script>
        function toggleDropdown(dotElement) {
            var dropdown = dotElement.nextElementSibling;
            var isVisible = dropdown.style.display === 'block';
            document.querySelectorAll('.dropdown-menu').forEach(function(menu) {
                menu.style.display = 'none';
            });
            if (!isVisible) {
                dropdown.style.display = 'block';
            }
        }

        window.onclick = function(event) {
            if (!event.target.matches('.three-dots')) {
                document.querySelectorAll('.dropdown-menu').forEach(function(menu) {
                    menu.style.display = 'none';
                });
            }
        }
        
        function toggleMenu() {
            var navbar = document.getElementById("navbar");
            var subjectContainer = document.getElementById("subject-container");
            navbar.classList.toggle("responsive");
            subjectContainer.classList.toggle("shift-down"); 
        }

        function deleteSubjectCard(buttonElement, subId) {
            if (confirm("Are you sure you want to delete this subject?")) {
                const xhr = new XMLHttpRequest();
                xhr.open("POST", "delete_subject.php", true);
                xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

                xhr.onreadystatechange = function() {
                    if (xhr.readyState === 4) {
                        if (xhr.status === 200) {
                            console.log(xhr.responseText);
                            const response = JSON.parse(xhr.responseText);
                            if (response.status === "success") {
                                const subjectCard = buttonElement.closest('.subject-card');
                                subjectCard.remove();
                            } else {
                                alert("Error deleting the subject: " + response.message);
                            }
                        } else {
                            alert("Request failed with status: " + xhr.status);
                        }
                    }
                };

                xhr.send("sub_id=" + subId);
            }
        }


        function toggleForm() {
            const form = document.getElementById('add-subject-form');
            form.style.display = form.style.display === 'block' ? 'none' : 'block'; 
        }

    function addSubject() {
        const title = document.getElementById('subject-title').value; 
        const description = document.getElementById('subject-desc').value; 

        if (!title || !description) {
            alert("Please fill in both fields.");
            return; 
        }

        const xhr = new XMLHttpRequest();
        xhr.open("POST", "add_subject.php", true);
        xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

        xhr.onload = function() {
            if (xhr.status >= 200 && xhr.status < 300) {
                const response = JSON.parse(xhr.responseText);
                if (response.status === "success") {
                    
                    document.getElementById('subject-title').value = '';
                    document.getElementById('subject-desc').value = '';

                    
                    const subjectContainer = document.querySelector('.subject-container');
                    const newCard = document.createElement('div');
                    newCard.classList.add('subject-card');
                    newCard.innerHTML = `<h2>${title}</h2><p>${description}</p><a href='studyrev.php'>Go to class</a>`;
                    subjectContainer.appendChild(newCard);
                    alert(response.message);
                    location.reload();
                } else {
                    alert("Error adding subject: " + response.message);
                }
            } else {
                alert("Error adding subject! Status: " + xhr.status);
            }
        };

        xhr.send(`title=${encodeURIComponent(title)}&description=${encodeURIComponent(description)}`);
    }
    </script>

</body>
</html>