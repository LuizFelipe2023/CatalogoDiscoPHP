<?php

require_once __DIR__ . '/../repositories/DiscoRepository.php';
require_once __DIR__ . '/../models/Disco.php';
require_once __DIR__ . '/../core/Csrf.php';
require_once __DIR__ . '/../core/Flash.php';
require_once __DIR__ . '/../core/View.php';

class DiscoController
{
    private DiscoRepository $repo;
    private array $allowedGeneros = ['Rock', 'Jazz', 'Pop', 'MPB', 'Eletrônica', 'Clássica', 'Outro'];
    private array $allowedFormatos = ['Vinil', 'CD', 'Cassete', 'Digital'];
    private array $allowedStatuses = ['Na Coleção', 'Desejado', 'Emprestado'];

    public function __construct()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $this->repo = new DiscoRepository();
    }

    public function index()
    {
        $userId = $_SESSION['user']['id'];

        $q = trim((string)($_GET['q'] ?? ''));
        $q = mb_substr($q, 0, 100);
        $q = $q !== '' ? $q : null;

        $status = $_GET['status'] ?? null;
        $status = is_string($status) && in_array($status, $this->allowedStatuses, true) ? $status : null;

        $genero = $_GET['genero'] ?? null;
        $genero = is_string($genero) && in_array($genero, $this->allowedGeneros, true) ? $genero : null;

        $formato = $_GET['formato'] ?? null;
        $formato = is_string($formato) && in_array($formato, $this->allowedFormatos, true) ? $formato : null;

        $minAvaliacao = $_GET['minAvaliacao'] ?? null;
        $maxAvaliacao = $_GET['maxAvaliacao'] ?? null;

        $minAvaliacao = is_numeric($minAvaliacao) ? (float)$minAvaliacao : null;
        $maxAvaliacao = is_numeric($maxAvaliacao) ? (float)$maxAvaliacao : null;

        if ($minAvaliacao !== null) {
            $minAvaliacao = max(0.0, min(10.0, $minAvaliacao));
        }
        if ($maxAvaliacao !== null) {
            $maxAvaliacao = max(0.0, min(10.0, $maxAvaliacao));
        }

        $sort = (string)($_GET['sort'] ?? 'nome_asc');
        $orderByMap = [
            'nome_asc' => 'nome ASC',
            'nome_desc' => 'nome DESC',
            'artista_asc' => 'artista ASC',
            'artista_desc' => 'artista DESC',
            'avaliacao_asc' => 'avaliacao ASC',
            'avaliacao_desc' => 'avaliacao DESC',
            'genero_asc' => 'genero ASC',
            'genero_desc' => 'genero DESC',
            'status_asc' => 'status ASC',
            'status_desc' => 'status DESC',
        ];
        $sortLabels = [
            'nome_asc' => 'Nome (A-Z)',
            'nome_desc' => 'Nome (Z-A)',
            'artista_asc' => 'Artista (A-Z)',
            'artista_desc' => 'Artista (Z-A)',
            'avaliacao_asc' => 'Avaliação (cresc.)',
            'avaliacao_desc' => 'Avaliação (decresc.)',
            'genero_asc' => 'Gênero (A-Z)',
            'genero_desc' => 'Gênero (Z-A)',
            'status_asc' => 'Status (A-Z)',
            'status_desc' => 'Status (Z-A)',
        ];
        $orderBy = $orderByMap[$sort] ?? $orderByMap['nome_asc'];

        $page = (int)($_GET['page'] ?? 1);
        $page = $page > 0 ? $page : 1;
        $perPage = 9;
        $offset = ($page - 1) * $perPage;

        $filters = [
            'q' => $q,
            'status' => $status,
            'genero' => $genero,
            'formato' => $formato,
            'minAvaliacao' => $minAvaliacao,
            'maxAvaliacao' => $maxAvaliacao,
        ];

        $total = $this->repo->countSearch($filters, $userId);
        $totalPages = (int)ceil($total / $perPage);
        if ($total === 0) {
            $totalPages = 0;
            $discos = [];
        } else {
            if ($page > $totalPages) {
                $page = $totalPages;
                $offset = ($page - 1) * $perPage;
            }
            $discos = $this->repo->search($filters, $userId, $perPage, $offset, $orderBy);
        }

        $csrf = Csrf::generate();

        View::render('discos/index', [
            'discos' => $discos,
            'filters' => [
                'q' => $q,
                'status' => $status,
                'genero' => $genero,
                'formato' => $formato,
                'minAvaliacao' => $minAvaliacao,
                'maxAvaliacao' => $maxAvaliacao,
                'sort' => $sort,
            ],
            'pagination' => [
                'page' => $page,
                'perPage' => $perPage,
                'total' => $total,
                'totalPages' => $totalPages,
            ],
            'queryParams' => [
                'q' => $q,
                'status' => $status,
                'genero' => $genero,
                'formato' => $formato,
                'minAvaliacao' => $minAvaliacao,
                'maxAvaliacao' => $maxAvaliacao,
                'sort' => $sort,
            ],
            'csrf' => $csrf,
            'generosOptions' => $this->allowedGeneros,
            'formatosOptions' => $this->allowedFormatos,
            'statusesOptions' => $this->allowedStatuses,
            'sortOptions' => $sortLabels,
        ]);
    }

    public function show(int $id)
    {
        $userId = $_SESSION['user']['id'];
        $disco = $this->repo->find($id, $userId);

        if (!$disco) {
            Flash::set('error', 'Disco não encontrado');
            header("Location: /");
            exit;
        }

        $csrf = Csrf::generate();

        View::render('discos/show', [
            'disco' => $disco,
            'csrf' => $csrf,
        ]);
    }

    public function create()
    {
        $csrf = Csrf::generate();

        View::render('discos/create', [
            'csrf' => $csrf
        ]);
    }

    public function store()
    {
        Csrf::validate($_POST['csrf'] ?? '');

        $data = $this->validate($_POST, '/create');
        $userId = $_SESSION['user']['id'];

        $capa = $this->uploadCapa('/create');

        $disco = new Disco(
            $data['nome'],
            $data['genero'],
            $data['artista'],
            (float) $data['avaliacao'],
            $data['formato'],
            $data['status'],
            $userId,
            null,
            $capa
        );

        $this->repo->create($disco);

        Flash::set('success', 'Disco criado com sucesso!');

        header("Location: /");
        exit;
    }

    public function edit(int $id)
    {
        $userId = $_SESSION['user']['id'];

        $disco = $this->repo->find($id, $userId);

        if (!$disco) {
            Flash::set('error', 'Disco não encontrado');
            header("Location: /");
            exit;
        }

        $csrf = Csrf::generate();

        View::render('discos/edit', [
            'disco' => $disco,
            'csrf' => $csrf
        ]);
    }

    public function update()
    {
        Csrf::validate($_POST['csrf'] ?? '');

        $idForRedirect = (int)($_POST['id'] ?? 0);
        $data = $this->validate($_POST, '/edit/' . $idForRedirect);
        $userId = $_SESSION['user']['id'];

        $discoAtual = $this->repo->find((int)$data['id'], $userId);

        if (!$discoAtual) {
            Flash::set('error', 'Disco não encontrado');
            header("Location: /");
            exit;
        }

        $novaCapa = $this->uploadCapa('/edit/' . (int)$data['id']);

        if ($novaCapa && $discoAtual->capa) {
            $path = __DIR__ . '/../../public/uploads/' . $discoAtual->capa;

            if (file_exists($path)) {
                unlink($path);
            }
        }

        $capaFinal = $novaCapa ?: $discoAtual->capa;

        $disco = new Disco(
            $data['nome'],
            $data['genero'],
            $data['artista'],
            (float) $data['avaliacao'],
            $data['formato'],
            $data['status'],
            $userId,
            (int) $data['id'],
            $capaFinal
        );

        $this->repo->update($disco);

        Flash::set('success', 'Disco atualizado!');

        header("Location: /");
        exit;
    }

    public function delete()
    {
        Csrf::validate($_POST['csrf'] ?? '');

        $id = (int) ($_POST['id'] ?? 0);
        $userId = $_SESSION['user']['id'];

        if ($id > 0) {
            $disco = $this->repo->find($id, $userId);

            if ($disco && $disco->capa) {
                $path = __DIR__ . '/../../public/uploads/' . $disco->capa;

                if (file_exists($path)) {
                    unlink($path); 
                }
            }

            $this->repo->delete($id, $userId);

            Flash::set('success', 'Disco removido!');
        } else {
            Flash::set('error', 'ID inválido');
        }

        header("Location: /");
        exit;
    }

    private function validate(array $data, string $redirectUrl): array
    {
        $nome = trim($data['nome'] ?? '');
        $artista = trim($data['artista'] ?? '');

        if (!$nome || !$artista) {
            Flash::set('error', 'Nome e artista são obrigatórios');
            header("Location: {$redirectUrl}");
            exit;
        }

        $genero = $data['genero'] ?? '';
        if (!is_string($genero) || $genero === '' || !in_array($genero, $this->allowedGeneros, true)) {
            Flash::set('error', 'Selecione um gênero válido');
            header("Location: {$redirectUrl}");
            exit;
        }

        $formato = $data['formato'] ?? '';
        if (!is_string($formato) || $formato === '' || !in_array($formato, $this->allowedFormatos, true)) {
            Flash::set('error', 'Selecione um formato válido');
            header("Location: {$redirectUrl}");
            exit;
        }

        $status = $data['status'] ?? '';
        if (!is_string($status) || $status === '' || !in_array($status, $this->allowedStatuses, true)) {
            Flash::set('error', 'Selecione um status válido');
            header("Location: {$redirectUrl}");
            exit;
        }

        $avaliacaoRaw = $data['avaliacao'] ?? 0;
        $avaliacao = is_numeric($avaliacaoRaw) ? (float)$avaliacaoRaw : 0.0;
        if ($avaliacao < 0.0 || $avaliacao > 10.0) {
            Flash::set('error', 'A avaliação precisa estar entre 0 e 10');
            header("Location: {$redirectUrl}");
            exit;
        }

        return [
            'id' => $data['id'] ?? null,
            'nome' => htmlspecialchars($nome),
            'genero' => htmlspecialchars($genero),
            'artista' => htmlspecialchars($artista),
            'avaliacao' => $avaliacao,
            'formato' => htmlspecialchars($formato),
            'status' => htmlspecialchars($status),
        ];
    }

    private function uploadCapa(string $redirectUrl): ?string
    {
        if (!isset($_FILES['capa'])) {
            return null;
        }

        if ($_FILES['capa']['error'] === UPLOAD_ERR_NO_FILE) {
            return null;
        }

        if ($_FILES['capa']['error'] !== UPLOAD_ERR_OK) {
            Flash::set('error', 'Falha no upload da capa.');
            header("Location: {$redirectUrl}");
            exit;
        }

        $file = $_FILES['capa'];

        $allowed = ['jpg', 'jpeg', 'png', 'webp'];
        $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));

        if (!in_array($ext, $allowed, true)) {
            Flash::set('error', 'Formato de imagem inválido. Use JPG, PNG ou WEBP.');
            header("Location: {$redirectUrl}");
            exit;
        }

        if ($file['size'] > 2 * 1024 * 1024) {
            Flash::set('error', 'A imagem precisa ter no máximo 2MB.');
            header("Location: {$redirectUrl}");
            exit;
        }

        $name = uniqid() . '.' . $ext;
        $destino = __DIR__ . '/../../public/uploads/' . $name;

        move_uploaded_file($file['tmp_name'], $destino);

        return $name;
    }
}
?>