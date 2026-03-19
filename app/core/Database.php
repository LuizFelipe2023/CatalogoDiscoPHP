<?php

class Database
{
    private static $pdo;

    public static function getConnection()
    {
        if (!self::$pdo) {
            $env = parse_ini_file(__DIR__ . '/../../.env');

            self::$pdo = new PDO(
                "mysql:host={$env['DB_HOST']};dbname={$env['DB_NAME']}",
                $env['DB_USER'],
                $env['DB_PASS'],
                [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
            );
        }

        return self::$pdo;
    }
}
?>