<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="container mt-4">
    <h2 class="mb-4">Create Tender</h2>
    
    <!-- Bootstrap Alert for API Messages -->
    <div id="apiMessage" class="alert d-none" role="alert"></div>

    <form id="tenderForm">
        <div class="mb-3">
            <label for="title" class="form-label">Title:</label>
            <input type="text" class="form-control" id="title" name="title" required placeholder="Enter tender title">
        </div>

        <div class="mb-3">
            <label for="description" class="form-label">Description:</label>
            <textarea class="form-control" id="description" name="description" rows="4" required placeholder="Provide a description for the tender"></textarea>
        </div>

        <div class="mb-3">
            <label for="visibility" class="form-label">Visibility:</label>
            <select class="form-select" id="visibility" name="visibility">
                <option value="public">Public</option>
                <option value="private">Private</option>
            </select>
        </div>

        <div class="mb-3">
            <label for="emailInput" class="form-label">Add Supplier Email:</label>
            <div class="input-group">
                <input type="email" class="form-control" id="emailInput" placeholder="Enter supplier email address">
                <button type="button" class="btn btn-secondary" id="addEmailButton">Add Email</button>
            </div>
            <small class="form-text text-muted">Click "Add Email" to add each email to the list.</small>
        </div>

        <div class="mb-3">
            <label class="form-label">Invited Suppliers:</label>
            <ul id="emailList" class="list-group"></ul>
        </div>

        <button type="button" class="btn btn-primary" id="createTenderButton">Create Tender</button>
    </form>
</div>

<!-- Modal for Confirmation -->
<div class="modal fade" id="confirmModal" tabindex="-1" aria-labelledby="confirmModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="confirmModalLabel">Confirm Creation</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Are you sure you want to create this tender?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="confirmCreateButton">OK</button>
            </div>
        </div>
    </div>
</div>

<script>
    const emailList = [];
    const emailInput = document.getElementById('emailInput');
    const emailListElement = document.getElementById('emailList');
    const addEmailButton = document.getElementById('addEmailButton');
    const createTenderButton = document.getElementById('createTenderButton');
    const confirmCreateButton = document.getElementById('confirmCreateButton');
    const apiMessage = document.getElementById('apiMessage');

    // Hàm lấy token từ cookie
    function getCookie(name) {
        const value = `; ${document.cookie}`;
        const parts = value.split(`; ${name}=`);
        if (parts.length === 2) return parts.pop().split(';').shift();
    }

    addEmailButton.addEventListener('click', function() {
        const email = emailInput.value.trim();
        const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

        if (emailPattern.test(email)) {
            emailList.push(email);
            const listItem = document.createElement('li');
            listItem.className = 'list-group-item';
            listItem.textContent = email;
            emailListElement.appendChild(listItem);

            emailInput.value = '';
        } else {
            showApiMessage('Please enter a valid email address.', 'danger');
        }
    });

    createTenderButton.addEventListener('click', function() {
        const confirmModal = new bootstrap.Modal(document.getElementById('confirmModal'));
        confirmModal.show();
    });

    confirmCreateButton.addEventListener('click', function() {
        const title = document.getElementById('title').value;
        const description = document.getElementById('description').value;
        const visibility = document.getElementById('visibility').value;

        const data = {
            title: title,
            description: description,
            visibility: visibility,
            invited_suppliers: emailList
        };

        const token = getCookie('token');

        if (!token) {
            showApiMessage('User is not authenticated.', 'danger');
            return;
        }

        fetch('<?= base_url('tender/createTender') ?>', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Authorization': `Bearer ${token}`
            },
            body: JSON.stringify(data)
        })
        .then(response => response.json())
        .then(result => {
            if (result.status === 'success') {
                showApiMessage('Tender created successfully!', 'success');
            } else {
                showApiMessage('Failed to create tender: ' + result.message, 'danger');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showApiMessage('An error occurred while creating the tender.', 'danger');
        });


        const confirmModal = bootstrap.Modal.getInstance(document.getElementById('confirmModal'));
        confirmModal.hide();
    });

    function showApiMessage(message, type) {
        apiMessage.textContent = message;
        apiMessage.className = `alert alert-${type}`;
        apiMessage.classList.remove('d-none');
    }
</script>

<?= $this->endSection() ?>