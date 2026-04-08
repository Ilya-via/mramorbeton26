<?php
declare(strict_types=1);

require_once __DIR__ . '/app.php';

function admin_current_user(): ?array
{
    app_start_session();
    $userId = $_SESSION[app_config()['admin']['session_key']] ?? null;
    if (!is_int($userId) && !ctype_digit((string) $userId)) {
        return null;
    }

    $pdo = app_pdo();
    if (!$pdo instanceof PDO || !app_db_ready()) {
        return null;
    }

    $stmt = $pdo->prepare(
        'SELECT id, username, display_name
         FROM admin_users
         WHERE id = :id AND is_active = 1
         LIMIT 1'
    );
    $stmt->execute(['id' => (int) $userId]);
    $user = $stmt->fetch();

    return is_array($user) ? $user : null;
}

function admin_has_users(): bool
{
    if (!app_db_ready()) {
        return false;
    }

    $pdo = app_pdo();
    if (!$pdo instanceof PDO) {
        return false;
    }

    try {
        return (int) $pdo->query('SELECT COUNT(*) FROM admin_users')->fetchColumn() > 0;
    } catch (Throwable $e) {
        return false;
    }
}

function admin_login(string $username, string $password): bool
{
    if (!app_db_ready()) {
        return false;
    }

    $pdo = app_pdo();
    if (!$pdo instanceof PDO) {
        return false;
    }

    $stmt = $pdo->prepare(
        'SELECT id, password_hash
         FROM admin_users
         WHERE username = :username AND is_active = 1
         LIMIT 1'
    );
    $stmt->execute(['username' => trim($username)]);
    $user = $stmt->fetch();

    if (!is_array($user) || !password_verify($password, (string) $user['password_hash'])) {
        return false;
    }

    app_start_session();
    session_regenerate_id(true);
    $_SESSION[app_config()['admin']['session_key']] = (int) $user['id'];

    return true;
}

function admin_logout(): void
{
    app_start_session();
    unset($_SESSION[app_config()['admin']['session_key']]);
    session_regenerate_id(true);
}

function admin_require_login(): void
{
    if (admin_current_user() !== null) {
        return;
    }

    $redirect = $_SERVER['REQUEST_URI'] ?? '/admin/';
    app_redirect('login.php?redirect=' . urlencode((string) $redirect));
}
