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
            align-items: center; /* Center items vertically */
            border-bottom: 3px solid rgb(210, 210, 71);
            padding: 0 20px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            box-sizing: border-box; /* Include padding in width calculation */
        }

        .navbar .logo {
            font-size: 24px;
            font-weight: bold;
            color: white;
            text-decoration: none;
            margin-right: 20px; /* Add space between logo and links */
        }

        .navbar-links {
            display: flex;
            align-items: center; /* Center links vertically */
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
                top: 8vh; /* Align with the navbar's height */
                left: 0;
                z-index: 10; /* Ensure the navbar is above other content */
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
            transition: margin-top 0.3s; /* Smooth transition */
        }

        .subject-container.shift-down {
            margin-top: 70px; /* Space for the expanded navbar */
        }

        .subject-card {
            background-color: #f4f4f4;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0px 4px 8px rgb(182, 181, 180);
            position: relative;
            transition: transform 0.2s; /* Smooth transition */
        }

        .subject-card:hover {
            box-shadow: 0px 8px 16px rgba(0, 0, 0, 0.2);
            transform: scale(1.02); /* Slight scale effect */
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
            background-color: maroon; /* Changed to maroon */
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
            border-radius: 4px;
            z-index: 15; /* Ensure dropdown menu is above other content */
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
    </style>
</head>
<body>

    <div class="navbar" id="navbar">
        <span class="menu-icon" onclick="toggleMenu()">&#9776;</span>
        <div class="navbar-links">
            <a href="study.php" class="logo">Study Buddy</a>
            <a href="Reminder.php">Reminders</a>
            <a href="quizzes.php">Quizzes</a>
        </div>
        <a href="studyout.php" class="logout">Log Out</a>
    </div>

    <div class="subject-container" id="subject-container">
        <div class="subject-card">
            <h2>Mathematics</h2>
            <p>Learn Algebra, Geometry, Calculus, and more.</p>
            <a href="studyrev.php">Go to class</a>
            <div class="three-dots" onclick="toggleDropdown(this)">⋮</div>
            <div class="dropdown-menu">
                <button onclick="editSubjectCard(this)">Edit</button>
                <button onclick="deleteSubjectCard(this)">Delete</button>
            </div>
        </div>

        <div class="subject-card">
            <h2>Science</h2>
            <p>Explore Physics, Chemistry, Biology, and Earth Sciences.</p>
            <a href="studyrev.php">Go to class</a>
            <div class="three-dots" onclick="toggleDropdown(this)">⋮</div>
            <div class="dropdown-menu">
                <button onclick="editSubjectCard(this)">Edit</button>
                <button onclick="deleteSubjectCard(this)">Delete</button>
            </div>
        </div>

        <div class="subject-card">
            <h2>History</h2>
            <p>Study world history, ancient civilizations, and modern events.</p>
            <a href="studyrev.php">Go to class</a>
            <div class="three-dots" onclick="toggleDropdown(this)">⋮</div>
            <div class="dropdown-menu">
                <button onclick="editSubjectCard(this)">Edit</button>
                <button onclick="deleteSubjectCard(this)">Delete</button>
            </div>
        </div>
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
            subjectContainer.classList.toggle("shift-down"); // Add or remove the shift-down class
        }

        function editSubjectCard(buttonElement) {
            const subjectCard = buttonElement.closest('.subject-card');
            const titleElement = subjectCard.querySelector('h2');
            const descriptionElement = subjectCard.querySelector('p');
            const newTitle = prompt("Edit the subject title:", titleElement.innerText);
            const newDescription = prompt("Edit the subject description:", descriptionElement.innerText);
            if (newTitle !== null && newDescription !== null) {
                titleElement.innerText = newTitle;
                descriptionElement.innerText = newDescription;
            }
        }

        function deleteSubjectCard(buttonElement) {
            const subjectCard = buttonElement.closest('.subject-card');
            subjectCard.remove();
        }
    </script>
</body>
</html>
