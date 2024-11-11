<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="h4">My Tenders (Contractor)</h2>
        <a href="<?= base_url('tender/create') ?>" class="btn btn-primary">Create New Tender</a>
    </div>

    <!-- Bootstrap Alert for API Messages -->
    <div id="apiMessage" class="alert d-none" role="alert"></div>

    <div class="card shadow-sm">
        <div class="card-body">
            <?php if (!empty($tenders) && is_array($tenders)): ?>
                <table class="table table-borderless align-middle">
                    <thead class="table-dark">
                        <tr>
                            <th>Title</th>
                            <th>Description</th>
                            <th>Visibility</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($tenders as $tender): ?>
                            <tr>
                                <td><?= esc($tender['title']) ?></td>
                                <td><?= esc($tender['description']) ?></td>
                                <td><?= esc($tender['visibility']) ?></td>
                                <td>
                                    <a href="<?= base_url('tender/edit/' . esc($tender['id'])) ?>" class="btn btn-sm btn-outline-primary me-2">
                                        <i class="bi bi-pencil"></i> Edit
                                    </a>
                                    <button class="btn btn-sm btn-outline-danger deleteButton" data-id="<?= esc($tender['id']) ?>" data-title="<?= esc($tender['title']) ?>">
                                        <i class="bi bi-trash"></i> Delete
                                    </button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p class="text-center">No tenders available.</p>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Modal for Confirm Delete -->
<div class="modal fade" id="confirmDeleteModal" tabindex="-1" aria-labelledby="confirmDeleteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="confirmDeleteModalLabel">Confirm Delete</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Are you sure you want to delete the tender "<span id="tenderTitle"></span>"?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger" id="confirmDeleteButton">Delete</button>
            </div>
        </div>
    </div>
</div>

<script>
    let tenderIdToDelete;
    document.querySelectorAll('.deleteButton').forEach(button => {
        button.addEventListener('click', function () {
            tenderIdToDelete = this.getAttribute('data-id');
            document.getElementById('tenderTitle').textContent = this.getAttribute('data-title');
            const confirmDeleteModal = new bootstrap.Modal(document.getElementById('confirmDeleteModal'));
            confirmDeleteModal.show();
        });
    });

    document.getElementById('confirmDeleteButton').addEventListener('click', function () {
        const token = getCookie('token');

        if (!token) {
            showApiMessage('User is not authenticated.', 'danger');
            return;
        }

        fetch(`<?= base_url('tender/delete') ?>/${tenderIdToDelete}`, {
            method: 'DELETE',
            headers: {
                'Authorization': `Bearer ${token}`
            }
        })
        .then(response => response.json())
        .then(result => {
            if (result.status === 'success') {
                showApiMessage(result.message, 'success');
                setTimeout(() => location.reload(), 1000);
            } else {
                showApiMessage(result.message, 'danger');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showApiMessage('An error occurred while deleting the tender.', 'danger');
        });

        const confirmDeleteModal = bootstrap.Modal.getInstance(document.getElementById('confirmDeleteModal'));
        confirmDeleteModal.hide();
    });

    function getCookie(name) {
        const value = `; ${document.cookie}`;
        const parts = value.split(`; ${name}=`);
        if (parts.length === 2) return parts.pop().split(';').shift();
    }

    function showApiMessage(message, type) {
        const apiMessage = document.getElementById('apiMessage');
        apiMessage.textContent = message;
        apiMessage.className = `alert alert-${type}`;
        apiMessage.classList.remove('d-none');
    }
</script>

<?= $this->endSection() ?>