<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BunTaska</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
</head>

<body class="m-5">
    <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form id="newTaskForm">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="staticBackdropLabel">New Task</h1>
                        <button type="reset" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <div class="modal-body text-start">
                        <div class="mb-3">
                            <label for="title" class="form-label">Title</label>
                            <input type="text" name="title" class="form-control" id="title" placeholder="Client Follow-up...">
                        </div>
                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea name="description" class="form-control" id="description" placeholder="Send updated contract draft to client...."></textarea>
                        </div>
                        <div class="mb-3">
                            <div class="form-check">
                                <input name="is_completed" class="form-check-input" value="1" type="checkbox" id="is_completed">
                                <label class="form-check-label" for="is_completed">
                                    Is Completed?
                                </label>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="due_date" class="form-label">Due Date</label>
                            <input type="date" name="due_date" class="form-control" id="due_date" placeholder="Client Follow-up...">
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="reset" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button onClick="submitTask()" type="button" class="btn btn-primary">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <h1 class="text-center">Task Manager👋</h1>
    <p class="text-center mb-5">Organize and complete work efficiently</p>
    <div style="display: flex;justify-content:center">
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#staticBackdrop">
            New Task
        </button>
        <div class="form-floating ms-3">
            <select class="form-select" id="filterSelect" aria-label="Floating label select example" style="max-width: 260px;">
                <option value="all" selected>All</option>
                <option value="completed">✅Completed</option>
                <option value="pending">⏳Pending</option>
            </select>
            <label for="filterSelect">Filter</label>
        </div>
    </div>


    <!-- <button onClick="showToast('Hello')" type="button" class="btn btn-primary" id="liveToastBtn">Show live toast</button> -->
    <div class="toast-container  position-fixed bottom-0 end-0 p-3">
        <div id="liveToast" class="toast text-bg-success" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="d-flex">
                <div class="toast-body">Hello, world! This is a toast message.</div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
        </div>
    </div>

    <br>
    <div class="table-responsive">
        <table class="table table-hover table-bordered">
            <thead class="table-dark">
                <tr>
                    <th scope="col">ID</th>
                    <th scope="col">Title</th>
                    <th scope="col">Description</th>
                    <th scope="col">Is Completed</th>
                    <th scope="col">Due Date</th>
                    <th scope="col">Action</th>
                </tr>
            </thead>
            <tbody id="data-body"></tbody>
        </table>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>

    <script>
        var tasks = [];
        loadData()

        document.getElementById('filterSelect').addEventListener('change', (event) => loadData());

        function toggleStatus(elemnt, taskId) {
            // work in progress
            task.is_completed = elemnt.checked;
            console.log('status toggle');
            console.log(elemnt.checked);
            return;
            fetch(`/api/tasks`, {
                    method: 'PATCH',
                    body: task
                })
                .then(res => {
                    return res.json();
                })
                .then(res => {
                    showToast('Task status updated');
                })
                // .then(data => renderTable(data.data))
                .catch(err => {
                    showToast('Something went wrong', 'danger')
                    console.error("Error:", err)
                });
        }

        function submitTask() {
            const formElement = document.querySelector("#newTaskForm");
            const formData = new FormData(formElement);

            fetch(`/api/tasks`, {
                    method: 'POST',
                    body: formData
                })
                .then(res => {
                    if (!res.ok) {
                        // Force the catch block to run, passing status + message
                        const error = new Error('HTTP error');
                        error.status = res.status;
                        error.body = res.text(); // optional
                        throw error;
                    }
                    return res.json();
                })
                .then(res => {
                    appendTaskToTable(res.data)
                    showToast('Task created successfully');
                    formElement.reset();
                    const myModalEl = document.getElementById('staticBackdrop');
                    const myModal = bootstrap.Modal.getInstance(myModalEl);
                    myModal.hide();
                    console.log(res.data)
                })
                // .then(data => renderTable(data.data))
                .catch(err => {
                    if (err.status) showToast('Validation failed', 'danger');
                    else showToast('Something went wrong', 'danger')
                    console.log(err.status);
                    // console.log(err.body);
                    console.error("Error:", err)
                });
        }

        function deleteTask(taskId) {
            fetch(`/api/tasks/${taskId}`, {
                    method: 'DELETE',
                })
                .then(res => {
                    return res.json()
                })
                .then(res => {
                    showToast('Task deleted successfully');
                    const deletedTaskRaw = document.querySelector(`#tr${taskId}`)
                    if (deletedTaskRaw) deletedTaskRaw.remove();
                })
                .catch(err => {
                    showToast('Something went wrong', 'danger')
                });
        }

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

            data.forEach(task => {
                // <div class="form-check">
                //             <input onchange="toggleStatus(this,${task.id})" class="form-check-input toggle-status" type="checkbox" role="switch"  ${task.is_completed?'checked':''}>
                //         </div>
                const row = `
                <tr id="tr${task.id}">
                    <td>${task.id}</td>
                    <td>${task.title}</td>
                    <td>${task.description??''}</td>
                    <td>${task.status}</td>
                    <td>${task.due_date??''}</td>
                    <td><div class="btn-group btn-group-sm" role="group" aria-label="Small button group">
                        <button type="button" class="btn btn-outline-primary">✏️</button>
                        <button type="button" onClick="deleteTask(${task.id})" class="btn btn-outline-danger">❌</button></div>
                    </td>
                </tr>
            `;
                body.innerHTML += row;
            });
        }

        function appendTaskToTable(task) {
            const body = document.getElementById("data-body");
            const row = `
                <tr id="tr${task.id}">
                    <td>${task.id}</td>
                    <td>${task.title}</td>
                    <td>${task.description??''}</td>
                    <td>${task.is_completed}</td>
                    <td>${task.due_date??''}</td>
                    <td><div class="btn-group btn-group-sm" role="group" aria-label="Small button group">
                        <button type="button" class="btn btn-outline-primary">✏️</button>
                        <button type="button" onClick="deleteTask(${task.id})" class="btn btn-outline-danger">❌</button></div>
                    </td>
                </tr>
            `;
            body.innerHTML += row;
        }

        function updateTaskToTable(task) {
            // work in progress
            console.log('updating task in table');
        }

        function showToast(msg = "Action success", color = 'success') {
            const toastLiveExample = document.getElementById('liveToast')
            msgElement = document.querySelector('.toast-body');
            msgElement.innerHTML = msg;
            const toastBootstrap = bootstrap.Toast.getOrCreateInstance(toastLiveExample)
            toastBootstrap.show()
        }
    </script>
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


<!-- remaining: task status toggle with patch, edit task -->