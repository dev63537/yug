<?php
require_once 'config/config.php';
logout_user();
redirect_with_message(APP_URL . '/login.php', 'You have been logged out successfully.', 'info');
