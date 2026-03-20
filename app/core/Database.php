<?php

class Database
{
    private static $pdo;

    public static function getConnection()
    {
        if (!self::$pdo) {
            $envPath = __DIR__ . '/../../env.php';

            if (file_exists($envPath)) {
                require_once $envPath;
            }

            try {
                $host    = getenv('DB_HOST');
                $dbname  = getenv('DB_NAME');
                $user    = getenv('DB_USER');
                $pass    = getenv('DB_PASS');
                $charset = getenv('DB_CHARSET') ?: 'utf8mb4';

                self::$pdo = new PDO(
                    "mysql:host=$host;dbname=$dbname;charset=$charset",
                    $user,
                    $pass,
                    [
                        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                        PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES $charset"
                    ]
                );
            } catch (PDOException $e) {
                die("Erro na conexão com o banco de dados: " . $e->getMessage());
            }
        }

        return self::$pdo;
    }
}