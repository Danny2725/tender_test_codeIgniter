<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tender Management System</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.8.1/font/bootstrap-icons.min.css">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="<?= base_url('assets/css/app.css') ?>">
</head>
<body>
<div class="d-flex">
    <!-- Sidebar -->
    <nav class="bg-dark text-white p-3 sidebar" style="width: 250px; height: 100vh; overflow-y: auto;">
        <a href="/" class="d-flex align-items-center mb-3 text-white text-decoration-none">
            <span class="fs-4">TenderApp</span>
        </a>
        <hr>
        <ul class="nav flex-column">
            <li class="nav-item mb-2">
                <a href="<?= base_url('tender/create') ?>" class="nav-link text-white">
                    <i class="bi bi-pencil-square me-2"></i> Create Tender
                </a>
            </li>
            <li class="nav-item mb-2">
                <a href="<?= base_url('tender/list_contractor') ?>" class="nav-link text-white">
                    <i class="bi bi-file-earmark-text me-2"></i> Tender list (Contractor)
                </a>
            </li>
            <li class="nav-item mb-2">
                <a href="<?= base_url('tender/list_supplier') ?>" class="nav-link text-white">
                    <i class="bi bi-box-arrow-in-right me-2"></i> Tender list (Supplier)
                </a>
            </li>
            <!-- Additional Sections -->
            <li class="nav-item mb-2">
                <a href="#" class="nav-link text-white">
                    <i class="bi bi-gear me-2"></i> Settings
                </a>
            </li>
            <li class="nav-item mb-2">
                <a href="<?= base_url('logout') ?>" class="nav-link text-white">
                    <i class="bi bi-door-open me-2"></i> Logout
                </a>
            </li>
        </ul>
    </nav>

    <!-- Main Content -->
    <div class="flex-grow-1">
        <!-- Top Navigation Bar -->
        <nav class="navbar navbar-expand-lg navbar-light bg-light">
            <div class="container-fluid">
                <a class="navbar-brand" href="#">Dashboard</a>
                <form class="d-flex ms-auto">
                    <input class="form-control me-2" type="search" placeholder="Search Here" aria-label="Search">
                </form>
                <div class="navbar-nav">
                    <a href="#" class="nav-link"><i class="bi bi-bell"></i></a>
                    <a href="#" class="nav-link"><i class="bi bi-gear"></i></a>
                    <a href="#" class="nav-link">Ross C. Lopez <i class="bi bi-person-circle ms-1"></i></a>
                </div>
            </div>
        </nav>

        <!-- Content Section -->
        <main class="p-4" style="background-color: #f8f9fa;">
            <div class="container-fluid">
                <?= $this->include('common/alerts'); ?>
                <?= $this->renderSection('content'); ?>
            </div>
        </main>
    </div>
</div>

<!-- Bootstrap Bundle with Popper -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
