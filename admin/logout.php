<?php
declare(strict_types=1);

require_once __DIR__ . '/bootstrap.php';

admin_logout();
app_flash_set('success', 'Вы вышли из админки.');
app_redirect('login.php');
