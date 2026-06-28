    </div><!-- /.main-content -->
    <footer class="footer">
        <div class="container">
            <div class="row g-4">
                <div class="col-lg-4 col-md-6">
                    <h5><i class="fas fa-calendar-alt text-primary me-2"></i><?= APP_NAME ?></h5>
                    <p class="mt-3"><?= APP_TAGLINE ?>. Book tickets for music concerts, tech conferences, workshops, sports events and cultural fests smoothly.</p>
                </div>
                <div class="col-lg-2 col-md-6">
                    <h5>Quick Links</h5>
                    <ul class="list-unstyled">
                        <li><a href="<?= APP_URL ?>/">Home</a></li>
                        <li><a href="<?= APP_URL ?>/events.php">Events</a></li>
                        <li><a href="<?= APP_URL ?>/about.php">About Us</a></li>
                        <li><a href="<?= APP_URL ?>/contact.php">Contact</a></li>
                    </ul>
                </div>
                <div class="col-lg-3 col-md-6">
                    <h5>User Portal</h5>
                    <ul class="list-unstyled">
                        <li><a href="<?= APP_URL ?>/login.php">User Login</a></li>
                        <li><a href="<?= APP_URL ?>/register.php">Register</a></li>
                        <li><a href="<?= APP_URL ?>/user/dashboard.php">User Dashboard</a></li>
                        <li><a href="<?= APP_URL ?>/organizer/dashboard.php">Organizer Portal</a></li>
                    </ul>
                </div>
                <div class="col-lg-3 col-md-6">
                    <h5>Contact Support</h5>
                    <p><i class="fas fa-envelope me-2 text-accent"></i><?= APP_EMAIL ?></p>
                    <p><i class="fas fa-phone me-2 text-accent"></i><?= APP_PHONE ?></p>
                    <p><i class="fas fa-map-marker-alt me-2 text-accent"></i><?= APP_ADDRESS ?></p>
                </div>
            </div>
            <div class="footer-bottom text-center">
                <p class="mb-0">&copy; <?= date('Y') ?> <?= APP_NAME ?>. Developed for BCA Major Project. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="<?= APP_URL ?>/assets/js/main.js"></script>
</body>
</html>
