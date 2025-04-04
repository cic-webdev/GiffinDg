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
        }

        .navbar-links {
            display: flex;
        }

        .navbar a {
            color: white;
            text-align: center;
            padding: 14px 20px;
            text-decoration: none;
            font-size: 23px;
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
            max-height: 200px;
            overflow-y: auto;
        }

        .task-card {
            background-color: #f4f4f4;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0px 4px 8px rgb(182, 181, 180);
            position: relative;
            transition: transform 0.2s;
        }

        .task-card:hover {
            box-shadow: 0px 8px 16px rgba(0, 0, 0, 0.2);
            transform: scale(1.02);
        }

        .add-task-btn {
            position: fixed;
            bottom: 20px; 
            right: 10%; 
            background-color: maroon;
            color: white;
            border: none;
            padding: 20px;
            border-radius: 50%;
            font-size: 20px;
            cursor: pointer;
            transition: background-color 0.3s;
            margin-bottom: 20px;
        }

        .add-task-btn:hover {
            background-color: rgb(224, 208, 64);
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
            color: black;
            border: none;
            padding: 10px;
            border-radius: 4px;
            cursor: pointer;
        }

        .modal button:hover {
            background-color: rgb(224, 208, 64);
        }

        .edit-btn {
            background-color: blue; 
            color: white; 
            border: none;
            padding: 10px;
            border-radius: 4px;
            cursor: pointer;
            margin-right: 5px; 
        }

        .delete-btn {
            background-color: red; 
            color: white; 
            border: none;
            padding: 10px;
            border-radius: 4px;
            cursor: pointer;
        }

        .edit-btn:hover,
        .delete-btn:hover {
            opacity: 0.8; 
        }
    </style>
</head>
<body>

    <div class="navbar" id="navbar">
        <span class="menu-icon" onclick="toggleMenu()">&#9776; Menu</span>
        <div class="navbar-links">
            <a href="study.php">Home</a>
            <a href="Reminder.php">Reminders</a>
        </div>
        <a href="logout.php" class="logout">Log Out</a>
    </div>

    <div class="task-container" id="task-container"></div>

    <button class="add-task-btn" onclick="openModal()">+</button>

    <div id="taskModal" class="modal">
        <div class="modal-content">
            <h2>Add New Task</h2>
            <label for="taskName">Task Name:</label>
            <input type="text" id="taskName">
            <label for="dueDate">Due Date:</label>
            <input type="date" id="dueDate">
            <label for="subjectName">Subject Name:</label>
            <input type="text" id="subjectName">
            <button onclick="addTask()">Add Task</button>
            <button onclick="closeModal()">Close</button>
        </div>
    </div>

    <script>
        const exampleTasks = [
            { name: "Math Homework", date: "2024-10-15", subject: "Mathematics" },
            { name: "Science Project", date: "2024-10-20", subject: "Science" },
            { name: "History Essay", date: "2024-10-25", subject: "History" }
        ];

        function addExampleTasks() {
            const taskContainer = document.getElementById('task-container');
            exampleTasks.forEach(task => {
                const newCard = document.createElement('div');
                newCard.classList.add('task-card');
                newCard.innerHTML = `
                    <h2>${task.name}</h2>
                    <p>Due Date: ${task.date}</p>
                    <p>Subject: ${task.subject}</p>
                    <button class="edit-btn" onclick="editTaskCard(this)">Edit</button>
                    <button class="delete-btn" onclick="deleteTaskCard(this)">Delete</button>
                `;
                taskContainer.appendChild(newCard);
            });
        }

        window.onload = addExampleTasks;

        function toggleMenu() {
            const navbar = document.getElementById("navbar");
            navbar.classList.toggle("responsive");
        }

        function openModal() {
            document.getElementById('taskModal').style.display = 'block';
        }

        function closeModal() {
            document.getElementById('taskModal').style.display = 'none';
        }

        function addTask() {
            const taskName = document.getElementById('taskName').value;
            const dueDate = document.getElementById('dueDate').value;
            const subjectName = document.getElementById('subjectName').value;

            if (!taskName || !dueDate || !subjectName) {
                alert("All fields must be filled out.");
                return;
            }

            const taskContainer = document.getElementById('task-container');
            const newCard = document.createElement('div');
            newCard.classList.add('task-card');
            newCard.innerHTML = `
                <h2>${taskName}</h2>
                <p>Due Date: ${dueDate}</p>
                <p>Subject: ${subjectName}</p>
                <button class="edit-btn" onclick="editTaskCard(this)">Edit</button>
                <button class="delete-btn" onclick="deleteTaskCard(this)">Delete</button>
            `;

            taskContainer.appendChild(newCard);
            closeModal();
        }

        function deleteTaskCard(buttonElement) {
            const taskCard = buttonElement.closest('.task-card');
            taskCard.remove();
        }

        function editTaskCard(buttonElement) {
            const taskCard = buttonElement.closest('.task-card');
            const titleElement = taskCard.querySelector('h2');
            const dueDateElement = taskCard.querySelector('p:nth-of-type(1)');
            const subjectElement = taskCard.querySelector('p:nth-of-type(2)');

            const newTitle = prompt("Edit the task name:", titleElement.innerText);
            const newDueDate = prompt("Edit the due date:", dueDateElement.innerText.split(": ")[1]);
            const newSubject = prompt("Edit the subject name:", subjectElement.innerText.split(": ")[1]);

            if (newTitle && newDueDate && newSubject) {
                titleElement.innerText = newTitle;
                dueDateElement.innerText = `Due Date: ${newDueDate}`;
                subjectElement.innerText = `Subject: ${newSubject}`;
            }
        }
    </script>

</body>
</html>
