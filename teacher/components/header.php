<nav class="navbar navbar-expand-lg navbar-dark nav-shadow bg-dark">
    <div class="container-fluid">
        <a class="navbar-brand h-font"><?php echo $faculty_details['faculty_name'] ?></a>
        <button class="navbar-toggler" type="button" data-bs-toggle="offcanvas" data-bs-target="#sidebar">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="btn-group">
            <button type="button" class="btn shadow-none text-white border border-secondary" data-bs-toggle="dropdown"
                data-bs-display="static" aria-expanded="false">
                <span><i class="bi bi-person-circle"></i> <?php echo $user_details['name'] ?></span>
            </button>
            <ul class="dropdown-menu dropdown-menu-lg-end">
                <li><a class="dropdown-item" href="logout.php">Log-out</a></li>
            </ul>
        </div>
    </div>
</nav>