<?php

require_once __DIR__ . '/../core/Database.php';
require_once __DIR__ . '/../models/Usuario.php';

class UsuarioRepository
{
    private $pdo;

    public function __construct()
    {
        $this->pdo = Database::getConnection();
    }

    public function getAllUsersOrdered()
    {
        $stmt = $this->pdo->prepare('SELECT * FROM usuarios ORDER BY nome ASC');
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function register(Usuario $usuario): bool
    {
        $passwordHash = password_hash($usuario->getPassword(), PASSWORD_DEFAULT);

        $stmt = $this->pdo->prepare(
            "INSERT INTO usuarios (nome, email, password, is_admin)
             VALUES (:nome, :email, :password, :is_admin)"
        );

        return $stmt->execute([
            ':nome'     => $usuario->nome,
            ':email'    => $usuario->getEmail(),
            ':password' => $passwordHash,
            ':is_admin' => $usuario->is_admin ? 1 : 0 
        ]);
    }

    public function getById(int $id): ?Usuario
    {
        $stmt = $this->pdo->prepare("SELECT * FROM usuarios WHERE id = :id");
        $stmt->execute([':id' => $id]);
        $data = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$data) return null;

        return new Usuario(
            $data['nome'],
            $data['email'],
            $data['password'],
            (bool)$data['is_admin'], 
            (int)$data['id']
        );
    }

    public function update(int $id, string $nome, string $email, bool $is_admin): bool
    {
        try {
            // Verifica se o novo email já pertence a OUTRO usuário
            $check = $this->pdo->prepare("SELECT id FROM usuarios WHERE email = :email AND id != :id");
            $check->execute([':email' => $email, ':id' => $id]);
            if ($check->fetch()) return false;

            $stmt = $this->pdo->prepare(
                "UPDATE usuarios SET nome = :nome, email = :email, is_admin = :is_admin WHERE id = :id"
            );

            return $stmt->execute([
                ':nome'     => $nome,
                ':email'    => $email,
                ':is_admin' => $is_admin ? 1 : 0,
                ':id'       => $id
            ]);
        } catch (PDOException $e) {
            return false;
        }
    }

    public function updatePassword(int $id, string $newPassword): bool
    {
        $hash = password_hash($newPassword, PASSWORD_DEFAULT);
        $stmt = $this->pdo->prepare("UPDATE usuarios SET password = :pass WHERE id = :id");
        return $stmt->execute([':pass' => $hash, ':id' => $id]);
    }


    public function delete(int $id): bool
    {
        try {
            $stmt = $this->pdo->prepare("DELETE FROM usuarios WHERE id = :id");
            return $stmt->execute([':id' => $id]) && $stmt->rowCount() > 0;
        } catch (PDOException $e) {
            error_log("Erro ao deletar usuário ID {$id}: " . $e->getMessage());
            return false;
        }
    }

    public function login(string $email, string $password): ?Usuario
    {
        $stmt = $this->pdo->prepare("SELECT * FROM usuarios WHERE email = :email");
        $stmt->execute([':email' => $email]);
        $data = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$data || !password_verify($password, $data['password'])) {
            return null;
        }

        return new Usuario(
            $data['nome'],
            $data['email'],
            $data['password'],
            (bool)$data['is_admin'], 
            (int)$data['id']
        );
    }
}