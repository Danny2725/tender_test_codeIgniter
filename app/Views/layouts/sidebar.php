<div class="d-flex flex-column flex-shrink-0 p-3 bg-light" style="width: 250px; height: 100vh;">
    <a href="/" class="d-flex align-items-center mb-3 mb-md-0 me-md-auto link-dark text-decoration-none">
        <span class="fs-4">Dashboard</span>
    </a>
    <hr>
    <ul class="nav nav-pills flex-column mb-auto">
        <li class="nav-item">
            <a href="/tender/create" class="nav-link link-dark <?= (uri_string() == 'tender/create' ? 'active' : '') ?>">
                <i class="bi bi-pencil-square"></i> Create Tender
            </a>
        </li>
        <li>
            <a href="/tender/list_contractor" class="nav-link link-dark <?= (uri_string() == 'tender/list_contractor' ? 'active' : '') ?>">
                <i class="bi bi-file-earmark-text"></i> My Tenders (Contractor)
            </a>
        </li>
        <li>
            <a href="/tender/list_supplier" class="nav-link link-dark <?= (uri_string() == 'tender/list_supplier' ? 'active' : '') ?>">
                <i class="bi bi-box-arrow-in-right"></i> Available Tenders (Supplier)
            </a>
        </li>
        <li>
            <a href="/login" class="nav-link link-dark <?= (uri_string() == 'login' ? 'active' : '') ?>">
                <i class="bi bi-door-open"></i> Login
            </a>
        </li>
    </ul>
</div>
