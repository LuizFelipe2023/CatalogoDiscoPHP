<?php

class Database
{
    private static $pdo;

    public static function getConnection()
    {
        if (!self::$pdo) {
          
            $configPath = __DIR__ . '/../../config.php';

            if (!file_exists($configPath)) {
              
                $configPath = $_SERVER['DOCUMENT_ROOT'] . '/config.php';
            }

            if (!file_exists($configPath)) {
                die("ERRO: Arquivo 'config.php' não encontrado na raiz do projeto.");
            }

           
            $config = require $configPath;
            $db = $config['db'];

            try {
                $host    = $db['host'];
                $dbname  = $db['name'];
                $user    = $db['user'];
                $pass    = $db['pass'];
                $charset = $db['charset'] ?? 'utf8mb4';

                $dsn = "mysql:host=$host;dbname=$dbname;charset=$charset";

                self::$pdo = new PDO($dsn, $user, $pass, [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES $charset"
                ]);
            } catch (PDOException $e) {
                die("Erro na conexão com o banco de dados: " . $e->getMessage());
            }
        }

        return self::$pdo;
    }
}