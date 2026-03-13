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
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
        </div>
        <div class="card">
            <div class="card-header d-flex justify-content-between">
                <h3>User Crud</h3>
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createModal">
                    Create User
                </button>
            </div>
            <div class="card-body">

            </div>
        </div>

        <!-- Modal -->
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
                            <input type="text" class="form-control" id="username" name="username" placeholder="Enter Username" value="Akshay">
                            <span id="username-message" class="d-none text-bold"></span>
                        </div>
                        <div class="form-group">
                            <label for="email" class="form-label">Email:</label>
                            <input type="email" class="form-control" id="email" name="email" placeholder="Enter Email" value="akshay@example.com">
                            <span id="email-message" class="d-none text-bold"></span>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary" onclick="storeForm()">Save</button>
                    </div>
                </div>
            </div>
        </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
    <script>

        const message = document.getElementById("message");
        const createModalEl = document.getElementById('createModal');
        let createModal = bootstrap.Modal.getInstance(createModalEl) || new bootstrap.Modal(createModalEl);
        const username = document.getElementById("username");
        const usernameMessage = document.getElementById("username-message");
        const email = document.getElementById("email");
        const emailMessage = document.getElementById("email-message");
        const createModalMessage = document.getElementById("create-modal-message");

        function storeForm() {

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

            fetch("{{ route('store') }}", {
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
    </script>
</body>
</html>
