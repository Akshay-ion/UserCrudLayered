<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>User Crud</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
</head>
<body>
    <div class="container mt-5">
        <div class="d-none alert alert-dismissible text-center" id="message">
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        <div class="card">
            <div class="card-header d-flex justify-content-between">
                <h3>User Crud</h3>
                <button type="button" class="btn btn-sm btn-primary " data-bs-toggle="modal" data-bs-target="#createModal">
                    Create User
                </button>
            </div>
            <div class="card-body">
                <input type="text" class="form-control mb-2" id="search" placeholder="Search Users">
                <div class="table-responsive overflow-auto" style="max-height:350px;">
                    <table class="table table-striped mb-0">
                        <thead class="table-light sticky-top">
                            <tr>
                                <th>#</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody id="usersTable">

                        </tbody>
                    </table>
                </div>
                <div id="table-loader" class="text-center my-3 d-none">
                    <div class="spinner-border text-primary"></div>
                </div>
            </div>
            <div class="card-footer d-flex justify-content-between align-items-center flex-wrap gap-2">
                <!-- Pagination -->
                <nav>
                    <ul class="pagination pagination-sm mb-0" id="pagination"></ul>
                </nav>

                <!-- Per Page -->
                <div class="d-flex align-items-center gap-2">
                    <label for="per-page" class="mb-0 fw-semibold">Rows:</label>
                    <select id="per-page" class="form-select form-select-sm w-auto">
                        <option value="5">5</option>
                        <option value="10">10</option>
                        <option value="25">25</option>
                        <option value="50">50</option>
                        <option value="100">100</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- create Modal -->
        <div class="modal fade" id="createModal" tabindex="-1" aria-labelledby="createModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="createModalLabel">Create User</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="d-none alert" id="create-modal-message"></div>

                        <div class="form-group">
                            <label for="username" class="form-label">Username:</label>
                            <input type="text" class="form-control" id="username" name="username" placeholder="Enter Username">
                            <span id="username-message" class="d-none text-bold"></span>
                        </div>
                        <div class="form-group">
                            <label for="email" class="form-label">Email:</label>
                            <input type="email" class="form-control" id="email" name="email" placeholder="Enter Email">
                            <span id="email-message" class="d-none text-bold"></span>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary" id="createBtn" onclick="storeForm()">Save</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Edit Modal -->
        <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="editModalLabel">Edit User</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div id="edit-modal-message" class="d-none alert"></div>

                        <div class="form-group">
                            <label for="edit-username" class="form-label">Username:</label>
                            <input type="text" class="form-control" id="edit-username" name="username" placeholder="Enter Username" value="Akshay">
                            <span id="edit-username-message" class="d-none text-bold"></span>
                        </div>
                        <div class="form-group">
                            <label for="edit-email" class="form-label">Email:</label>
                            <input type="email" class="form-control" id="edit-email" name="email" placeholder="Enter Email" value="akshay@example.com">
                            <span id="edit-email-message" class="d-none text-bold"></span>
                        </div>
                        <input type="hidden" id="userId" value="">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary" id="updateBtn" onclick="updateForm()">Update</button>
                    </div>
                </div>
            </div>
        </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
    <script>

        let currentPage = 1;
        let currentPerPage = 5;
        let currentSearch = "";

        document.addEventListener("DOMContentLoaded", function () {
            currentSearch = document.getElementById("search").value;
            currentPerPage = document.getElementById("per-page").value;

            getUsers();
        });

        let searchTimer;

        document.getElementById("search").addEventListener("keyup", function(){

            clearTimeout(searchTimer);

            searchTimer = setTimeout(() => {

                currentSearch = this.value;

                getUsers(1, currentSearch, currentPerPage);

            }, 400);

        });

        document.getElementById("per-page").addEventListener("change", function(){

            let newPerPage = parseInt(this.value);

            let currentIndex = (currentPage - 1) * currentPerPage;

            let newPage = Math.floor(currentIndex / newPerPage) + 1;

            getUsers(newPage, currentSearch, newPerPage);
        });

        function getUsers(page = 1, search = currentSearch, perPage = currentPerPage){

            const loader = document.getElementById("table-loader");
            const table = document.getElementById("usersTable");

            loader.classList.remove("d-none");
            table.innerHTML = "";

            currentPage = page;
            currentSearch = search;
            currentPerPage = perPage;

            fetch(`/user?page=${page}&search=${encodeURIComponent(search)}&perPage=${perPage}`)
            .then(response => response.json())
            .then(data => {
                let users = data.data.data;
                let html = "";

                if(users.length === 0){
                    html = `<tr><td colspan="4" class="text-center">No Users Found</td></tr>`;
                } else {
                    users.forEach(({id, name, email}, index) => {
                        html += `
                            <tr id="user-row-${id}">
                                <td>${(currentPage - 1) * currentPerPage + index + 1}</td>
                                <td>${name}</td>
                                <td>${email}</td>
                                <td>
                                    <div class="btn btn-sm btn-info" onclick="editUser(${id})">Edit</div>
                                    <div class="btn btn-sm btn-danger" onclick="deleteUser(${id})">Delete</div>
                                </td>
                            </tr>
                        `;
                    });
                }

                table.innerHTML = html;
                renderPagination(data.data);
            })
            .catch(error => console.error(error))
            .finally(() => {
                loader.classList.add("d-none");
            });
        }

        function renderPagination(data){

            let html = "";

            let current = data.current_page;
            let last = data.last_page;

            let start = Math.max(current - 2, 1);
            let end = Math.min(current + 2, last);

            // Previous
            html += `
                <li class="page-item ${current === 1 ? 'disabled' : ''}">
                    <button class="page-link" onclick="${current > 1 ? `getUsers(${current - 1})` : ''}">Previous</button>
                </li>
            `;

            // First page
            if(start > 1){
                html += `
                    <li class="page-item">
                        <button class="page-link" onclick="getUsers(1)">1</button>
                    </li>
                `;
                html += `<li class="page-item disabled"><span class="page-link">...</span></li>`;
            }

            // Page numbers
            for(let i = start; i <= end; i++){
                html += `
                    <li class="page-item ${i === current ? 'active' : ''}">
                        <button class="page-link" onclick="getUsers(${i})">${i}</button>
                    </li>
                `;
            }

            // Last page
            if(end < last){
                html += `<li class="page-item disabled"><span class="page-link">...</span></li>`;
                html += `
                    <li class="page-item">
                        <button class="page-link" onclick="getUsers(${last})">${last}</button>
                    </li>
                `;
            }

            // Next
            html += `
                <li class="page-item ${current === last ? 'disabled' : ''}">
                    <button class="page-link"
                        onclick="${current < last ? `getUsers(${current + 1})` : ''}">
                        Next
                    </button>
                </li>
            `;

            document.getElementById("pagination").innerHTML = html;
        }

        const message = document.getElementById("message");
        const createModalEl = document.getElementById('createModal');
        let createModal = bootstrap.Modal.getInstance(createModalEl) || new bootstrap.Modal(createModalEl);
        const username = document.getElementById("username");
        const usernameMessage = document.getElementById("username-message");
        const email = document.getElementById("email");
        const emailMessage = document.getElementById("email-message");
        const createModalMessage = document.getElementById("create-modal-message");

        function storeForm() {

            const createBtn = document.getElementById("createBtn");

            // Disable button and show "Saving..."
            createBtn.disabled = true;
            createBtn.innerText = "Saving...";

            // Reset previous messages
            username.classList.remove("is-invalid");
            usernameMessage.classList.add("d-none");
            email.classList.remove("is-invalid");
            emailMessage.classList.add("d-none");
            createModalMessage.classList.add("d-none");
            createModalMessage.classList.remove("alert", "alert-danger");

            if (!username.value) {
                username.classList.add("is-invalid");
                usernameMessage.classList.remove("d-none");
                usernameMessage.classList.add("text-danger");
                usernameMessage.innerText = "Username is required";
                return;
            }

            if (!email.value) {
                email.classList.add("is-invalid");
                emailMessage.classList.remove("d-none");
                emailMessage.classList.add("text-danger");
                emailMessage.innerText = "Email is required";
                return;
            }

            fetch("{{ route('user.store') }}", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "Accept": "application/json",
                    "X-CSRF-TOKEN": "{{ csrf_token() }}"
                },
                body: JSON.stringify({
                    name: username.value,
                    email: email.value,
                })
            })
            .then(async response => {
                const data = await response.json();
                if (!response.ok) throw data;
                return data;
            })
            .then(data => {
                // Show success message
                message.classList.remove("d-none", "alert-danger");
                message.classList.add("alert-success", "alert-dismissible", "fade", "show");
                message.innerHTML = `
                    ${data.message}
                    <button type="button" class="btn-close" aria-label="Close" onclick="closeMessage()"></button>
                `;

                getUsers(currentPage, currentSearch, currentPerPage);
                createModal.hide();
                resetStoreForm();
            })
            .catch(error => {
                // Handle validation errors
                if (error.errors) {
                    if (error.errors.name) {
                        username.classList.add("is-invalid");
                        usernameMessage.classList.remove("d-none");
                        usernameMessage.classList.add("text-danger");
                        usernameMessage.innerText = error.errors.name[0];
                    }
                    if (error.errors.email) {
                        email.classList.add("is-invalid");
                        emailMessage.classList.remove("d-none");
                        emailMessage.classList.add("text-danger");
                        emailMessage.innerText = error.errors.email[0];
                    }
                }

                // General error message in modal
                if (error.message) {
                    createModalMessage.classList.remove("d-none");
                    createModalMessage.classList.add("alert", "alert-danger", "alert-dismissible", "fade", "show");
                    createModalMessage.innerHTML = `
                        ${error.message}
                        <button type="button" class="btn-close" aria-label="Close" onclick="closeModalMessage()"></button>
                    `;
                }
            })
            .finally(() => {
                createBtn.disabled = false;
                createBtn.innerText = "Save";
            });
        }

        function resetStoreForm() {
            username.value = "";
            email.value = "";
            username.classList.remove("is-invalid");
            usernameMessage.classList.add("d-none");
            usernameMessage.classList.remove("text-danger");
            email.classList.remove("is-invalid");
            emailMessage.classList.add("d-none");
            emailMessage.classList.remove("text-danger");
        }

        function closeMessage() {
            message.classList.add("d-none");
        }

        function closeModalMessage() {
            createModalMessage.classList.add("d-none");
        }

        function deleteUser(id){
            const deleteBtn = document.querySelector(`#user-row-${id} .btn-danger`);

            if(!confirm("Are you sure you want to delete this user?")) return;

            deleteBtn.disabled = true;
            deleteBtn.innerText = "Deleting...";

            fetch(`/user/${id}`, {
                method: "DELETE",
                headers: {
                    "Content-Type": "application/json",
                    "Accept": "application/json",
                    "X-CSRF-TOKEN": "{{ csrf_token() }}"
                }
            })
            .then(response => response.json())
            .then(data => {

                const row = document.getElementById(`user-row-${id}`);
                if(row) row.remove();

                // check remaining rows
                const rows = document.querySelectorAll("#usersTable tr");

                if(rows.length === 0 && currentPage > 1){
                    currentPage--;
                }

                getUsers(currentPage, currentSearch, currentPerPage);

                message.classList.remove("d-none");
                message.classList.add("alert-success");
                message.innerHTML = `
                    ${data.message}
                    <button type="button" class="btn-close" onclick="closeMessage()"></button>
                `;
            })
            .catch(error => console.error(error))
            .finally(() => {
                deleteBtn.disabled = false;
                deleteBtn.innerText = "Delete";
            });
        }

        function editUser(userId){
            const editModalEl = document.getElementById('editModal');
            let editModal = bootstrap.Modal.getInstance(editModalEl) || new bootstrap.Modal(editModalEl);
            const userIdInput = document.getElementById("userId");
            const username = document.getElementById("edit-username");
            const usernameMessage = document.getElementById("edit-username-message");
            const email = document.getElementById("edit-email");
            const emailMessage = document.getElementById("edit-email-message");
            const editModalMessage = document.getElementById("edit-modal-message");

            const url = "{{ route('user.show', ':id') }}".replace(':id', userId);
            fetch(url, {
                method: 'GET',
                headers: {
                    'Content-Type': 'application/json',
                }
            })
            .then(response => response.json())
            .then(data => {
                console.log(data);

                if(data.status === 200){
                    userIdInput.value = data.data.id
                    username.value = data.data.name;
                    email.value = data.data.email;

                    editModal.show();
                }
            })
            .catch(error => console.error(error));
        }

        function updateForm() {

            const updateBtn = document.getElementById("updateBtn");

            updateBtn.disabled = true;
            updateBtn.innerText = "Updating...";

            const userIdInput = document.getElementById("userId");
            const username = document.getElementById("edit-username");
            const usernameMessage = document.getElementById("edit-username-message");
            const email = document.getElementById("edit-email");
            const emailMessage = document.getElementById("edit-email-message");
            const editModalMessage = document.getElementById("edit-modal-message");

            // Reset previous errors
            username.classList.remove("is-invalid");
            usernameMessage.classList.add("d-none");
            email.classList.remove("is-invalid");
            emailMessage.classList.add("d-none");
            editModalMessage.classList.add("d-none");
            editModalMessage.classList.remove("alert-danger");

            if (!username.value) {
                username.classList.add("is-invalid");
                usernameMessage.classList.remove("d-none");
                usernameMessage.classList.add("text-danger");
                usernameMessage.innerText = "Username is required";

                updateBtn.disabled = false;
                updateBtn.innerText = "Update";
                return;
            }

            if (!email.value) {
                email.classList.add("is-invalid");
                emailMessage.classList.remove("d-none");
                emailMessage.classList.add("text-danger");
                emailMessage.innerText = "Email is required";

                updateBtn.disabled = false;
                updateBtn.innerText = "Update";
                return;
            }

            const url = `/user/${userIdInput.value}`; // Laravel RESTful update route

            fetch(url, {
                method: 'PUT', // Use PUT for updating
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': "{{ csrf_token() }}"
                },
                body: JSON.stringify({
                    name: username.value,
                    email: email.value
                })
            })
            .then(async response => {
                const data = await response.json();
                if (!response.ok) throw data;
                return data;
            })
            .then(data => {
                // ✅ Update the row dynamically without full table reload
                const row = document.getElementById(`user-row-${userIdInput.value}`);
                if(row) {
                    row.children[1].innerText = username.value; // Name column
                    row.children[2].innerText = email.value;    // Email column
                }

                // Show success message
                const message = document.getElementById("message");
                message.classList.remove("d-none", "alert-danger");
                message.classList.add("alert-success", "alert-dismissible", "fade", "show");
                message.innerHTML = `
                    ${data.message}
                    <button type="button" class="btn-close" aria-label="Close" onclick="closeMessage()"></button>
                `;

                // Hide edit modal
                const editModalEl = document.getElementById('editModal');
                const editModal = bootstrap.Modal.getInstance(editModalEl);
                editModal.hide();
            })
            .catch(error => {
                // Handle validation errors
                if (error.errors) {
                    if (error.errors.name) {
                        username.classList.add("is-invalid");
                        usernameMessage.classList.remove("d-none");
                        usernameMessage.classList.add("text-danger");
                        usernameMessage.innerText = error.errors.name[0];
                    }
                    if (error.errors.email) {
                        email.classList.add("is-invalid");
                        emailMessage.classList.remove("d-none");
                        emailMessage.classList.add("text-danger");
                        emailMessage.innerText = error.errors.email[0];
                    }
                }

                // General error message
                if (error.message) {
                    editModalMessage.classList.remove("d-none");
                    editModalMessage.classList.add("alert-danger");
                    editModalMessage.innerHTML = `
                        ${error.message}
                        <button type="button" class="btn-close" onclick="this.parentElement.classList.add('d-none')"></button>
                    `;
                }
            })
            .finally(() => {
                updateBtn.disabled = false;
                updateBtn.innerText = "Update";
            });
        }

        // const createModalEl = document.getElementById('createModal');

        createModalEl.addEventListener('hidden.bs.modal', function () {

            const username = document.getElementById("username");
            const email = document.getElementById("email");
            const userId = document.getElementById("userId");

            const usernameMessage = document.getElementById("username-message");
            const emailMessage = document.getElementById("email-message");
            const createModalMessage = document.getElementById("create-modal-message");

            if (username) {
                username.value = "";
                username.classList.remove("is-invalid", "is-valid");
            }

            if (email) {
                email.value = "";
                email.classList.remove("is-invalid", "is-valid");
            }

            if (userId) userId.value = "";

            if (usernameMessage) {
                usernameMessage.classList.add("d-none");
                usernameMessage.innerText = "";
            }

            if (emailMessage) {
                emailMessage.classList.add("d-none");
                emailMessage.innerText = "";
            }

            if (createModalMessage) {
                createModalMessage.classList.add("d-none");
                createModalMessage.innerText = "";
            }
        });

        const editModalEl = document.getElementById('editModal');

        editModalEl.addEventListener('hidden.bs.modal', function () {

            const username = document.getElementById("edit-username");
            const email = document.getElementById("edit-email");
            const userId = document.getElementById("userId");

            const usernameMessage = document.getElementById("edit-username-message");
            const emailMessage = document.getElementById("edit-email-message");
            const editModalMessage = document.getElementById("edit-modal-message");

            if (username) {
                username.value = "";
                username.classList.remove("is-invalid", "is-valid");
            }

            if (email) {
                email.value = "";
                email.classList.remove("is-invalid", "is-valid");
            }

            if (userId) userId.value = "";

            if (usernameMessage) {
                usernameMessage.classList.add("d-none");
                usernameMessage.innerText = "";
            }

            if (emailMessage) {
                emailMessage.classList.add("d-none");
                emailMessage.innerText = "";
            }

            if (editModalMessage) {
                editModalMessage.classList.add("d-none");
                editModalMessage.innerText = "";
            }
        });
    </script>
</body>
</html>
