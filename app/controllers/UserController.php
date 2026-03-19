<?php

require_once __DIR__ . '/../repositories/UsuarioRepository.php'; 
require_once __DIR__ . '/../models/Usuario.php'; 
require_once __DIR__ . '/../core/View.php';
require_once __DIR__ . '/../core/Flash.php'; 
require_once __DIR__ . '/../core/Csrf.php';
require_once __DIR__ . '/../core/Auth.php';

class UserController
{
    private $repo;

    public function __construct()
    {
        Auth::requireAdmin();
        $this->repo = new UsuarioRepository();
    }

    public function index()
    {
        $usuarios = $this->repo->getAllUsersOrdered();

        View::render('users/index', [
            'usuarios' => $usuarios,
            'title'    => 'Gerenciar Usuários',
            'csrf'     => Csrf::generate() 
        ]);
    }

    public function create()
    {
        View::render('users/create', [
            'title' => 'Novo Usuário',
            'csrf'  => Csrf::generate()
        ]);
    }

    public function store()
    {
        Csrf::validate($_POST['csrf'] ?? '');

        $nome     = trim($_POST['nome'] ?? '');
        $email    = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        $isAdmin  = isset($_POST['is_admin']) ? true : false;

        if (empty($nome) || empty($email) || empty($password)) {
            Flash::set('error', 'Preencha todos os campos obrigatórios.');
            header("Location: /users/create");
            exit;
        }

        $novoUsuario = new Usuario($nome, $email, $password, $isAdmin);

        if ($this->repo->register($novoUsuario)) {
            Flash::set('success', "Usuário {$nome} cadastrado com sucesso!");
            header("Location: /users");
        } else {
            Flash::set('error', 'Erro ao cadastrar. O e-mail informado já está em uso.');
            header("Location: /users/create");
        }
        exit;
    }

    public function edit($id)
    {
        $usuario = $this->repo->getById((int)$id);

        if (!$usuario) {
            Flash::set('error', 'Usuário não encontrado.');
            header("Location: /users");
            exit;
        }

        View::render('users/edit', [
            'usuario' => $usuario,
            'title'   => 'Editar Usuário',
            'csrf'    => Csrf::generate()
        ]);
    }

    public function update()
    {
        Csrf::validate($_POST['csrf'] ?? '');

        $id       = (int)($_POST['id'] ?? 0);
        $nome     = trim($_POST['nome'] ?? '');
        $email    = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? ''; 
        $isAdmin  = isset($_POST['is_admin']) ? true : false;

        if (empty($nome) || empty($email)) {
            Flash::set('error', 'Nome e E-mail são obrigatórios.');
            header("Location: /users/edit/" . $id);
            exit;
        }

        $sucesso = $this->repo->update($id, $nome, $email, $isAdmin);

        if ($sucesso) {
            if (!empty($password)) {
                $this->repo->updatePassword($id, $password);
            }

            Flash::set('success', "Usuário {$nome} atualizado com sucesso!");
            header("Location: /users");
        } else {
            Flash::set('error', 'Erro ao atualizar. O e-mail pode já estar em uso.');
            header("Location: /users/edit/" . $id);
        }
        exit;
    }

    public function delete()
    {
        Csrf::validate($_POST['csrf'] ?? '');

        $id = (int)($_POST['id'] ?? 0);
        $meuId = Auth::id(); 

        if ($id === $meuId) {
            Flash::set('error', 'Você não pode excluir sua própria conta!');
            header("Location: /users");
            exit;
        }

        if ($id > 0) {
            if ($this->repo->delete($id)) {
                Flash::set('success', 'Usuário removido com sucesso!');
            } else {
                Flash::set('error', 'Erro ao remover: O usuário pode ter discos vinculados.');
            }
        }

        header("Location: /users");
        exit;
    }
}