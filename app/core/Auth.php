<?php

class Auth
{
    public static function check(): bool
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        return isset($_SESSION['user']);
    }

    public static function isAdmin(): bool
    {
        return self::check() && (bool)($_SESSION['user']['is_admin'] ?? false);
    }

    public static function user(): ?array
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        return $_SESSION['user'] ?? null;
    }

    public static function id(): ?int
    {
        return self::user()['id'] ?? null;
    }

    public static function requireLogin(): void
    {
        if (!self::check()) {
            header("Location: /login");
            exit;
        }
    }

    public static function requireAdmin(): void
    {
        self::requireLogin(); 

        if (!self::isAdmin()) {
            header("Location: /colecao");
            exit;
        }
    }
}