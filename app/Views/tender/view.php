<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="h4">Tender Details</h2>
        <a href="<?= base_url('tender/list_supplier') ?>" class="btn btn-secondary">Back to List</a>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            <h5 class="card-title"><?= esc($tender['title']) ?></h5>
            <p class="card-text"><strong>Description:</strong> <?= esc($tender['description']) ?></p>
            <p class="card-text"><strong>Visibility:</strong> <?= ucfirst(esc($tender['visibility'])) ?></p>

            <?php if ($tender['visibility'] === 'private' && !empty($invitedSuppliers)): ?>
                <div class="mt-4">
                    <h6>Invited Suppliers:</h6>
                    <ul class="list-group">
                        <?php foreach ($invitedSuppliers as $supplier): ?>
                            <li class="list-group-item"><?= esc($supplier['supplier_email']) ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>
<?= $this->endSection() ?>