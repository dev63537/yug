<nav class="navbar navbar-expand-lg navbar-custom sticky-top">
    <div class="container">
        <a class="navbar-brand" href="<?= APP_URL ?>/"><i class="fas fa-calendar-alt text-primary me-2"></i><?= APP_NAME ?></a>
        <button class="navbar-toggler text-white" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <i class="fas fa-bars"></i>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto align-items-center">
                <li class="nav-item"><a class="nav-link" href="<?= APP_URL ?>/">Home</a></li>
                <li class="nav-item"><a class="nav-link" href="<?= APP_URL ?>/events.php">Events</a></li>
                <li class="nav-item"><a class="nav-link" href="<?= APP_URL ?>/categories.php">Categories</a></li>
                <li class="nav-item"><a class="nav-link" href="<?= APP_URL ?>/about.php">About Us</a></li>
                <li class="nav-item"><a class="nav-link" href="<?= APP_URL ?>/contact.php">Contact</a></li>
                
                <?php if (is_logged_in()): ?>
                    <li class="nav-item dropdown ms-lg-3">
                        <a class="nav-link dropdown-toggle btn btn-outline-light px-3 py-1 text-white" href="#" id="userDrop" role="button" data-bs-toggle="dropdown">
                            <i class="fas fa-user-circle me-1"></i><?= xss_clean($_SESSION['user_name']) ?>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-menu-item dropdown-item" href="<?= get_dashboard_url() ?>"><i class="fas fa-tachometer-alt me-2"></i>Dashboard</a></li>
                            <li><a class="dropdown-item" href="<?= APP_URL ?>/user/profile.php"><i class="fas fa-user me-2"></i>My Profile</a></li>
                            <li><a class="dropdown-item" href="<?= APP_URL ?>/user/bookings.php"><i class="fas fa-ticket-alt me-2"></i>My Bookings</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item text-danger" href="<?= APP_URL ?>/logout.php"><i class="fas fa-sign-out-alt me-2"></i>Logout</a></li>
                        </ul>
                    </li>
                <?php else: ?>
                    <li class="nav-item ms-lg-3"><a class="nav-link btn btn-outline-light px-3 py-1 text-white me-2" href="<?= APP_URL ?>/login.php">Login</a></li>
                    <li class="nav-item"><a class="btn btn-accent px-3 py-1" href="<?= APP_URL ?>/register.php">Register</a></li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>
