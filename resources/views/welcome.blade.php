<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BunTaska</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
</head>

<body class="m-5">

    <h1 class="text-center">Task Manager👋</h1>
    <p class="text-center mb-5">Organize and complete work efficiently</p>

    <div class="row align-items-start text-center">
        <div class="col">
            <button onClick="showToast('hello')">New Task</button>
        </div>
        <div class="col">
            <button onClick="showToast('hello')">Toast message</button>
        </div>
        <div class="col">
            <div class="form-floating mx-5">
                <select class="form-select" id="filterSelect" aria-label="Floating label select example">
                    <option value="all" selected>All</option>
                    <option value="completed">✅Completed</option>
                    <option value="pending">⏳Pending</option>
                </select>
                <label for="filterSelect">Filter</label>
            </div>
        </div>
    </div>

    <br>

    <table class="table table-hover table-bordered">
        <thead>
            <tr>
                <th scope="col">ID</th>
                <th scope="col">Title</th>
                <th scope="col">Description</th>
                <th scope="col">Status</th>
                <th scope="col">Due Date</th>
                <th scope="col">Action</th>
            </tr>
        </thead>
        <tbody id="data-body"></tbody>
    </table>


    <script>
        var tasks = [];

        loadData()

        document.getElementById('filterSelect').addEventListener('change', (event) => loadData());

        function loadData() {
            const filter = document.getElementById("filterSelect").value;

            fetch(`/api/tasks?filter=${filter}`)
                .then(res => res.json())
                .then(data => renderTable(data.data))
                .catch(err => console.error("Error:", err));
        }

        function renderTable(data) {
            const body = document.getElementById("data-body");
            body.innerHTML = "";

            data.forEach(item => {
                const row = `
                <tr>
                    <td>${item.id}</td>
                    <td>${item.title}</td>
                    <td>${item.description??''}</td>
                    <td>${item.is_completed?'Completed':'Pending'}</td>
                    <td>${item.due_date??''}</td>
                    <td><div class="btn-group btn-group-sm" role="group" aria-label="Small button group">
                        <button type="button" class="btn btn-outline-primary">✏️</button>
                        <button type="button" class="btn btn-outline-danger">❌</button></div>
                    </td>
                </tr>
            `;
                body.innerHTML += row;
            });
        }

        function renderTasksTable(tasks) {
            console.log('renderTasksTable...');
        }

        function removeTaskFromTable(task) {
            console.log('removeTaskFromTable');
        }

        function addTaskToTable(task) {
            console.log('remove item from table');
        }

        function updateTaskToTable(task) {
            console.log('remove item from table');
        }

        function showToast(msg, color = 'green') {
            alert(msg)
        }
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
</body>

</html>

<!--
- create task form, task list must update dynamically upon successful creation
-
-Fetch and render all tasks dynamically when the page loads.
- bootstrap toaster
- a form that sends an AJAX request to the API.
* The task list must update dynamically upon successful creation.
 -->