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

        #add-form {
            display: none;
            margin: 20px auto;
            width: 300px;
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
        }

        #add-form input[type="text"],
        #add-form textarea,
        #add-form input[type="file"] {
            width: 92%;
            padding: 10px;
            margin: 10px 0;
            border-radius: 4px;
            border: 1px solid #ccc;
        }

        #add-form button {
            width: 100%;
            padding: 10px;
            background-color: maroon;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        #add-form button:hover {
            background-color: rgb(210, 210, 71);
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

        $sql = "SELECT * FROM `tblunit`";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                echo "<div class='subject-card'>";
                echo "<h2>" . htmlspecialchars($row['title']) . "</h2>";
                echo "<p>" . htmlspecialchars($row['description']) . "</p>";
                echo "<a href='" . htmlspecialchars($row['file_path']) . "' target='_blank'>View PPT</a>";
                echo "<input type='hidden' class='subject-id' value='" . $row['id'] . "'>";
                echo "<div class='three-dots' onclick='toggleDropdown(this)'>⋮</div>";
                echo "<div class='dropdown-menu'>";
                echo "<button onclick='editSubjectCard(this)'>Edit</button>";
                echo "<button onclick='deleteSubjectCard(this)'>Delete</button>";
                echo "</div>";
                echo "</div>";
            }
        } else {
            echo "<p>No Units available.</p>";
        }

        $conn->close();
        ?>
    </div>

    <button class="add-button" onclick="toggleForm()"></button> 

    <div id="add-form">
        <form id="subjectForm" enctype="multipart/form-data">
            <h3>Add New Subject</h3>
            <input type="text" id="new-title" name="title" placeholder="Enter subject title" required>
            <textarea id="new-description" name="description" placeholder="Enter subject description" required></textarea>
            <input type="file" id="ppt-file" name="pptFile" accept=".ppt, .pptx" required>
            <button type="button" onclick="addSubjectCard()">Add Subject</button>
        </form>
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

        function toggleForm() {
            var form = document.getElementById("add-form");
            form.style.display = form.style.display === "block" ? "none" : "block";
        }

        function deleteSubjectCard(buttonElement) {
            const subjectCard = buttonElement.closest('.subject-card');
            const id = subjectCard.querySelector('.subject-id').value;

            if (confirm('Are you sure you want to delete this subject?')) {
                fetch('delete_unit.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ id })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'success') {
                        alert('Subject deleted successfully.');
                        subjectCard.remove();
                    } else {
                        alert(data.message || 'Delete failed.');
                    }
                })
                .catch(error => console.error('Error:', error));
            }
        }


        function editSubjectCard(buttonElement) {
            const subjectCard = buttonElement.closest('.subject-card');
            const id = subjectCard.querySelector('.subject-id').value;
            const newTitle = prompt("Edit the subject title:", subjectCard.querySelector('h2').innerText);
            const newDescription = prompt("Edit the subject description:", subjectCard.querySelector('p').innerText);

            if (newTitle && newDescription) {
                fetch('edit_unit.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ id, title: newTitle, description: newDescription })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'success') {
                        alert('Subject updated successfully.');
                        subjectCard.querySelector('h2').innerText = newTitle;
                        subjectCard.querySelector('p').innerText = newDescription;
                    } else {
                        alert(data.message || 'Update failed.');
                    }
                })
                .catch(error => console.error('Error:', error));
            }
        }


    function addSubjectCard() {
        const form = document.getElementById('subjectForm');
        const formData = new FormData(form);

        fetch('add_unit.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                alert('Subject added successfully.');
                form.reset();
                location.reload();
            } else {
                alert(data.message || 'An error occurred.');
            }
        })
        .catch(error => console.error('Error:', error));
    }

    </script>
</body>
</html>