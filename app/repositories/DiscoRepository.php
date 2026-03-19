<?php

require_once __DIR__ . '/../core/Database.php';
require_once __DIR__ . '/../models/Disco.php';

class DiscoRepository
{
    private $pdo;

    public function __construct()
    {
        $this->pdo = Database::getConnection();
    }

    public function all(int $userId): array
    {
        $stmt = $this->pdo->prepare(
            "SELECT * FROM discos WHERE user_id = :user_id ORDER BY nome"
        );

        $stmt->execute([':user_id' => $userId]);

        $discos = [];

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $discos[] = Disco::fromArray($row);
        }

        return $discos;
    }

    public function search(
        array $filters,
        int $userId,
        int $limit,
        int $offset,
        string $orderBy
    ): array {
        $limit = (int)$limit;
        $offset = (int)$offset;

        $where = "WHERE user_id = :user_id";
        $params = [':user_id' => $userId];

        if (!empty($filters['q'])) {
            $where .= " AND (nome LIKE :q OR artista LIKE :q OR genero LIKE :q)";
            $params[':q'] = '%' . $filters['q'] . '%';
        }

        if (!empty($filters['status'])) {
            $where .= " AND status = :status";
            $params[':status'] = $filters['status'];
        }

        if (!empty($filters['genero'])) {
            $where .= " AND genero = :genero";
            $params[':genero'] = $filters['genero'];
        }

        if (!empty($filters['formato'])) {
            $where .= " AND formato = :formato";
            $params[':formato'] = $filters['formato'];
        }

        if (!empty($filters['minAvaliacao']) || $filters['minAvaliacao'] === 0.0) {
            $where .= " AND avaliacao >= :minAvaliacao";
            $params[':minAvaliacao'] = (float)$filters['minAvaliacao'];
        }

        if (!empty($filters['maxAvaliacao']) || $filters['maxAvaliacao'] === 0.0) {
            $where .= " AND avaliacao <= :maxAvaliacao";
            $params[':maxAvaliacao'] = (float)$filters['maxAvaliacao'];
        }

       
        $sql = "SELECT * FROM discos {$where} ORDER BY {$orderBy} LIMIT {$limit} OFFSET {$offset}";
        $stmt = $this->pdo->prepare($sql);

        $stmt->execute($params);

        $discos = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $discos[] = Disco::fromArray($row);
        }

        return $discos;
    }

    public function countSearch(array $filters, int $userId): int
    {
        $where = "WHERE user_id = :user_id";
        $params = [':user_id' => $userId];

        if (!empty($filters['q'])) {
            $where .= " AND (nome LIKE :q OR artista LIKE :q OR genero LIKE :q)";
            $params[':q'] = '%' . $filters['q'] . '%';
        }

        if (!empty($filters['status'])) {
            $where .= " AND status = :status";
            $params[':status'] = $filters['status'];
        }

        if (!empty($filters['genero'])) {
            $where .= " AND genero = :genero";
            $params[':genero'] = $filters['genero'];
        }

        if (!empty($filters['formato'])) {
            $where .= " AND formato = :formato";
            $params[':formato'] = $filters['formato'];
        }

        if (!empty($filters['minAvaliacao']) || $filters['minAvaliacao'] === 0.0) {
            $where .= " AND avaliacao >= :minAvaliacao";
            $params[':minAvaliacao'] = (float)$filters['minAvaliacao'];
        }

        if (!empty($filters['maxAvaliacao']) || $filters['maxAvaliacao'] === 0.0) {
            $where .= " AND avaliacao <= :maxAvaliacao";
            $params[':maxAvaliacao'] = (float)$filters['maxAvaliacao'];
        }

        $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM discos {$where}");
        $stmt->execute($params);

        $total = $stmt->fetchColumn();
        return (int) ($total ?: 0);
    }

    public function find(int $id, int $userId): ?Disco
    {
        $stmt = $this->pdo->prepare(
            "SELECT * FROM discos WHERE id = :id AND user_id = :user_id"
        );

        $stmt->execute([
            ':id' => $id,
            ':user_id' => $userId
        ]);

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        return $row ? Disco::fromArray($row) : null;
    }

    public function create(Disco $disco): void
    {
        $stmt = $this->pdo->prepare("
            INSERT INTO discos 
            (nome, genero, artista, avaliacao, formato, status, user_id, capa)
            VALUES 
            (:nome, :genero, :artista, :avaliacao, :formato, :status, :user_id, :capa)
        ");

        $stmt->execute([
            ':nome' => $disco->nome,
            ':genero' => $disco->genero,
            ':artista' => $disco->artista,
            ':avaliacao' => $disco->avaliacao,
            ':formato' => $disco->formato,
            ':status' => $disco->status,
            ':user_id' => $disco->user_id,
            ':capa' => $disco->capa 
        ]);
    }

    public function update(Disco $disco): void
    {
        $stmt = $this->pdo->prepare("
            UPDATE discos SET
                nome = :nome,
                genero = :genero,
                artista = :artista,
                avaliacao = :avaliacao,
                formato = :formato,
                status = :status,
                capa = :capa
            WHERE id = :id AND user_id = :user_id
        ");

        $stmt->execute([
            ':id' => $disco->id,
            ':nome' => $disco->nome,
            ':genero' => $disco->genero,
            ':artista' => $disco->artista,
            ':avaliacao' => $disco->avaliacao,
            ':formato' => $disco->formato,
            ':status' => $disco->status,
            ':capa' => $disco->capa, 
            ':user_id' => $disco->user_id
        ]);
    }

    public function delete(int $id, int $userId): void
    {
        $stmt = $this->pdo->prepare(
            "DELETE FROM discos WHERE id = :id AND user_id = :user_id"
        );

        $stmt->execute([
            ':id' => $id,
            ':user_id' => $userId
        ]);
    }
}
?>