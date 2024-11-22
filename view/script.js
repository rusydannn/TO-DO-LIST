const apiUrl = 'http://localhost//todo_51422152/index.php';

        // Fetch tasks and display them
        function fetchTasks() {
            fetch(apiUrl)
                .then(response => response.json())
                .then(data => {
                    const taskList = document.getElementById('taskList');
                    taskList.innerHTML = '';
                    data.forEach(task => {
                        const row = document.createElement('tr');
                        row.innerHTML = `
                            <td>${task.id}</td>
                            <td class="${task.completed == 1 ? 'completed' : ''}">${task.title}</td>
                            <td>${task.completed == 1 ? 'Yes' : 'No'}</td>
                            <td>
                                <button onclick="completeTask(${task.id})">Complete</button>
                                <button onclick="deleteTask(${task.id})">Delete</button>
                            </td>
                        `;
                        taskList.appendChild(row);
                    });
                })
                .catch(error => console.error('Error fetching tasks:', error));
        }

        // Add a new task
        document.getElementById('taskForm').addEventListener('submit', function(e) {
            e.preventDefault();
            const title = document.getElementById('taskTitle').value;

            fetch(apiUrl, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ title })
            })
                .then(response => response.json())
                .then(data => {
                    alert(data.message);
                    fetchTasks();
                    document.getElementById('taskForm').reset();
                })
                .catch(error => console.error('Error adding task:', error));
        });

        // Complete a task
        function completeTask(id) {
            fetch(apiUrl, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ id })
            })
                .then(response => response.json())
                .then(data => {
                    alert(data.message);
                    fetchTasks();
                })
                .catch(error => console.error('Error completing task:', error));
        }

        // Delete a task
        function deleteTask(id) {
            fetch(apiUrl, {
                method: 'DELETE',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ id })
            })
                .then(response => response.json())
                .then(data => {
                    alert(data.message);
                    fetchTasks();
                })
                .catch(error => console.error('Error deleting task:', error));
        }

        // Initial fetch of tasks
        fetchTasks();