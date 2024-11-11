<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="h4">Available Tenders (Supplier)</h2>
    </div>

    <!-- Tabs for Public and Private Tenders -->
    <ul class="nav nav-tabs" id="tenderTabs" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" id="public-tab" data-bs-toggle="tab" data-bs-target="#public" type="button" role="tab" aria-controls="public" aria-selected="true">
                Public Tenders
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="private-tab" data-bs-toggle="tab" data-bs-target="#private" type="button" role="tab" aria-controls="private" aria-selected="false">
                Private Tenders
            </button>
        </li>
    </ul>

    <div class="tab-content" id="tenderTabsContent">
        <!-- Public Tenders Tab -->
        <div class="tab-pane fade show active" id="public" role="tabpanel" aria-labelledby="public-tab">
            <div class="card shadow-sm mt-4">
                <div class="card-body">
                    <?php if (!empty($publicTenders) && is_array($publicTenders)): ?>
                        <table class="table table-borderless align-middle">
                            <thead class="table-dark">
                                <tr>
                                    <th>Title</th>
                                    <th>Description</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($publicTenders as $tender): ?>
                                    <tr>
                                        <td><?= esc($tender['title']) ?></td>
                                        <td><?= esc($tender['description']) ?></td>
                                        <td>
                                            <a href="<?= base_url('tender/view/' . esc($tender['id'])) ?>" class="btn btn-sm btn-outline-primary me-2">
                                                <i class="bi bi-eye"></i> View
                                            </a>
                                            <a href="<?= base_url('tender/apply/' . esc($tender['id'])) ?>" class="btn btn-sm btn-outline-success">
                                                <i class="bi bi-check-circle"></i> Apply
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    <?php else: ?>
                        <p class="text-center">No public tenders available.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Private Tenders Tab -->
        <div class="tab-pane fade" id="private" role="tabpanel" aria-labelledby="private-tab">
            <div class="card shadow-sm mt-4">
                <div class="card-body">
                    <?php if (!empty($privateTenders) && is_array($privateTenders)): ?>
                        <table class="table table-borderless align-middle">
                            <thead class="table-dark">
                                <tr>
                                    <th>Title</th>
                                    <th>Description</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($privateTenders as $tender): ?>
                                    <tr>
                                        <td><?= esc($tender['title']) ?></td>
                                        <td><?= esc($tender['description']) ?></td>
                                        <td>
                                            <a href="<?= base_url('tender/view/' . esc($tender['id'])) ?>" class="btn btn-sm btn-outline-primary me-2">
                                                <i class="bi bi-eye"></i> View
                                            </a>
                                            <a href="<?= base_url('tender/apply/' . esc($tender['id'])) ?>" class="btn btn-sm btn-outline-success">
                                                <i class="bi bi-check-circle"></i> Apply
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    <?php else: ?>
                        <p class="text-center">No private tenders available.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>