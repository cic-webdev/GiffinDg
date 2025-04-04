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

            .navbar a.icon {
                float: right;
                display: block;
            }

            .menu-icon {
                display: block;
            }

            .navbar.responsive {
                position: relative;
            }

            .navbar.responsive .menu-icon {
                position: absolute;
                right: 0;
                top: 0;
            }

            .navbar.responsive a {
                float: none;
                display: block;
                text-align: left;
            }

            .navbar.responsive .navbar-right {
                float: none;
            }
        }

        .subject-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            padding: 20px;
            margin-top: 20px;
        
        }

        .subject-card {
            background-color: #f4f4f4;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0px 4px 8px grey;
            position: relative;
        }

        .subject-card:hover {
            box-shadow: 0px 8px 16px rgba(0, 0, 0, 0.2);
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
            background-color: maroon;
            color: white;
            text-decoration: none;
            border-radius: 4px;
            transition: background-color 0.3s;
        }

        .subject-card a:hover {
            background-color: rgb(255, 230, 0);
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
            background-color: #fff;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
            border-radius: 4px;
            z-index: 1;
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

        .add-subject-btn {
            position: fixed;
            bottom: 20px; 
            right: 10%; 
            background-color: maroon;
            color: white;
            border: none;
            padding: 20px;
            border-radius: 47%;
            font-size: 20px;
            cursor: pointer;
            transition: background-color 0.3s;
            margin-bottom: 20px;
        }

        .add-subject-btn:hover {
            background-color: rgb(255, 230, 0);
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

<div class="subject-container" id="subject-container">
<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "dbStuddyBuddy";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT id, title, note FROM tblNotes";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo "<div class='subject-card'>";
        echo "<h3>" . htmlspecialchars($row['title']) . "</h3>";
        echo "<p>" . htmlspecialchars($row['note']) . "</p>";
        echo "<a href='Reviewer viewer.php?id=" . $row['id'] . "'>View</a>";
        echo "<div class='three-dots' onclick='toggleDropdown(this)'>⋮</div>";
        echo "<div class='dropdown-menu'>";
        echo "<button onclick='editSubjectCard(this, " . $row['id'] . ")'>Edit</button>";
        echo "<button onclick='deleteSubjectCard(this, " . $row['id'] . ")'>Delete</button>";
        echo "</div>";
        echo "</div>";
    }
} else {
    echo "<p style='text-align:center;'>No notes available.</p>";
}
$conn->close();
?>
    </div>

    <a href="ReviewMaker copy.php"><button class="add-subject-btn" >+</button></a>

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
            if (navbar.className === "navbar") {
                navbar.className += " responsive";
            } else {
                navbar.className = "navbar";
            }
        }

        function deleteSubjectCard(buttonElement) {
            const subjectCard = buttonElement.closest('.subject-card');
            subjectCard.remove();
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

        function deleteSubjectCard(buttonElement, id) {
    if (confirm("Are you sure you want to delete this note?")) {
        fetch("delete_note.php", {
            method: "POST",
            headers: { "Content-Type": "application/x-www-form-urlencoded" },
            body: `id=${id}`
        })
        .then(response => response.text())
        .then(data => {
            console.log(data);
            const subjectCard = buttonElement.closest('.subject-card');
            subjectCard.remove();
        })
        .catch(error => console.error('Error:', error));
    }
}

function editSubjectCard(buttonElement, id) {
    const subjectCard = buttonElement.closest('.subject-card');
    const titleElement = subjectCard.querySelector('h3');
    const descriptionElement = subjectCard.querySelector('p');

    const newTitle = prompt("Edit the subject title:", titleElement.innerText);
    const newDescription = prompt("Edit the subject description:", descriptionElement.innerText);

    if (newTitle !== null && newDescription !== null) {
        fetch("edit_note.php", {
            method: "POST",
            headers: { "Content-Type": "application/x-www-form-urlencoded" },
            body: `id=${id}&title=${encodeURIComponent(newTitle)}&note=${encodeURIComponent(newDescription)}`
        })
        .then(response => response.text())
        .then(data => {
            console.log(data);
            titleElement.innerText = newTitle;
            descriptionElement.innerText = newDescription;
        })
        .catch(error => console.error('Error:', error));
    }
}

    </script>

</body>
</html>