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

function admin_list_users(): array
{
    if (!app_db_ready()) {
        return [];
    }

    $pdo = app_pdo();
    if (!$pdo instanceof PDO) {
        return [];
    }

    $stmt = $pdo->query(
        'SELECT id, username, display_name, is_active, created_at
         FROM admin_users
         ORDER BY created_at ASC, id ASC'
    );

    $rows = $stmt->fetchAll();
    return is_array($rows) ? $rows : [];
}

function admin_change_password(int $userId, string $currentPassword, string $newPassword, string $confirmPassword): void
{
    if ($userId <= 0) {
        throw new RuntimeException('Пользователь не найден.');
    }
    if ($newPassword === '' || strlen($newPassword) < 8) {
        throw new RuntimeException('Новый пароль должен быть не короче 8 символов.');
    }
    if (!hash_equals($newPassword, $confirmPassword)) {
        throw new RuntimeException('Подтверждение пароля не совпадает.');
    }

    $pdo = app_pdo();
    if (!$pdo instanceof PDO || !app_db_ready()) {
        throw new RuntimeException('База данных недоступна.');
    }

    $stmt = $pdo->prepare(
        'SELECT password_hash
         FROM admin_users
         WHERE id = :id AND is_active = 1
         LIMIT 1'
    );
    $stmt->execute(['id' => $userId]);
    $row = $stmt->fetch();
    if (!is_array($row)) {
        throw new RuntimeException('Пользователь не найден.');
    }

    if (!password_verify($currentPassword, (string) $row['password_hash'])) {
        throw new RuntimeException('Текущий пароль указан неверно.');
    }

    $update = $pdo->prepare(
        'UPDATE admin_users
         SET password_hash = :password_hash
         WHERE id = :id
         LIMIT 1'
    );
    $update->execute([
        'id' => $userId,
        'password_hash' => password_hash($newPassword, PASSWORD_DEFAULT),
    ]);
}

function admin_create_user(string $username, string $displayName, string $password, string $confirmPassword): void
{
    $username = trim($username);
    $displayName = trim($displayName);

    if ($username === '') {
        throw new RuntimeException('Укажите логин пользователя.');
    }
    if (!preg_match('/^[a-zA-Z0-9._-]{3,100}$/', $username)) {
        throw new RuntimeException('Логин: 3-100 символов, только латиница, цифры и ._-');
    }
    if ($displayName === '') {
        throw new RuntimeException('Укажите отображаемое имя.');
    }
    if ($password === '' || strlen($password) < 8) {
        throw new RuntimeException('Пароль должен быть не короче 8 символов.');
    }
    if (!hash_equals($password, $confirmPassword)) {
        throw new RuntimeException('Подтверждение пароля не совпадает.');
    }

    $pdo = app_pdo();
    if (!$pdo instanceof PDO || !app_db_ready()) {
        throw new RuntimeException('База данных недоступна.');
    }

    $existsStmt = $pdo->prepare(
        'SELECT COUNT(*)
         FROM admin_users
         WHERE username = :username'
    );
    $existsStmt->execute(['username' => $username]);
    if ((int) $existsStmt->fetchColumn() > 0) {
        throw new RuntimeException('Пользователь с таким логином уже существует.');
    }

    $insert = $pdo->prepare(
        'INSERT INTO admin_users (username, password_hash, display_name, is_active)
         VALUES (:username, :password_hash, :display_name, 1)'
    );
    $insert->execute([
        'username' => $username,
        'password_hash' => password_hash($password, PASSWORD_DEFAULT),
        'display_name' => $displayName,
    ]);
}
