<?php

require_once __DIR__ . '/../repositories/UsuarioRepository.php';
require_once __DIR__ . '/../models/Usuario.php';
require_once __DIR__ . '/../core/Csrf.php';
require_once __DIR__ . '/../core/Flash.php';
require_once __DIR__ . '/../core/View.php';
require_once __DIR__ . '/../core/Auth.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;


require_once __DIR__ . '/../core/libs/PHPMailer/Exception.php';
require_once __DIR__ . '/../core/libs/PHPMailer/PHPMailer.php';
require_once __DIR__ . '/../core/libs/PHPMailer/SMTP.php';
class AuthController
{
    private UsuarioRepository $repo;

    public function __construct()
    {
        $this->repo = new UsuarioRepository();

        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }


    public function loginForm()
    {
        View::render('auth/login', [
            'csrf' => Csrf::generate(),
            'title' => 'Entrar no Catálogo'
        ]);
    }

    public function login()
    {
        Csrf::validate($_POST['csrf'] ?? '');

        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';

        if (!$email || !$password) {
            Flash::set('error', 'Por favor, preencha o e-mail e a senha.');
            header("Location: /login");
            exit;
        }

        $usuario = $this->repo->login($email, $password);

        if (!$usuario) {
            Flash::set('error', 'Credenciais inválidas. Tente novamente.');
            header("Location: /login");
            exit;
        }

        $_SESSION['user'] = [
            'id' => $usuario->id,
            'nome' => $usuario->nome,
            'email' => $usuario->getEmail(),
            'is_admin' => (bool) $usuario->is_admin
        ];

        Flash::set('success', "Olá, {$usuario->nome}! Bem-vindo de volta.");

        $this->redirectBasedOnRole();
    }

    public function registerForm()
    {
        if (Auth::check()) {
            header("Location: /colecao");
            exit;
        }

        View::render('auth/register', [
            'csrf' => Csrf::generate(),
            'title' => 'Criar Nova Conta'
        ]);
    }

    public function register()
    {
        Csrf::validate($_POST['csrf'] ?? '');

        $nome = trim($_POST['nome'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';

        if (!$nome || !$email || !$password) {
            Flash::set('error', 'Todos os campos são obrigatórios.');
            header("Location: /register");
            exit;
        }

        $usuario = new Usuario($nome, $email, $password, false);

        if ($this->repo->register($usuario)) {
            Flash::set('success', 'Conta criada com sucesso! Agora você pode entrar.');
            header("Location: /login");
        } else {
            Flash::set('error', 'Não foi possível criar a conta. O e-mail informado já está em uso.');
            header("Location: /register");
        }
        exit;
    }


    public function logout()
    {
        $_SESSION = [];

        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(
                session_name(),
                '',
                time() - 42000,
                $params["path"],
                $params["domain"],
                $params["secure"],
                $params["httponly"]
            );
        }

        session_destroy();
        header("Location: /login");
        exit;
    }

    public function profile()
    {
        Auth::requireLogin();

        View::render('auth/profile', [
            'user' => $_SESSION['user'],
            'csrf' => Csrf::generate(),
            'title' => 'Meu Perfil'
        ]);
    }

    public function updateProfile()
    {
        Auth::requireLogin();
        Csrf::validate($_POST['csrf'] ?? '');

        $id = (int) $_SESSION['user']['id'];
        $nome = trim($_POST['nome'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $isAdmin = (bool) ($_SESSION['user']['is_admin'] ?? false);

        if (empty($nome) || empty($email)) {
            Flash::set('error', 'Nome e E-mail não podem ficar vazios.');
            header("Location: /profile");
            exit;
        }

        if ($this->repo->update($id, $nome, $email, $isAdmin)) {
            $_SESSION['user']['nome'] = $nome;
            $_SESSION['user']['email'] = $email;
            Flash::set('success', 'Perfil atualizado com sucesso!');
        } else {
            Flash::set('error', 'Erro ao atualizar: E-mail já cadastrado por outro usuário.');
        }

        header("Location: /profile");
        exit;
    }

    private function redirectBasedOnRole()
    {
        if (Auth::isAdmin()) {
            header("Location: /users");
        } else {
            header("Location: /colecao");
        }
        exit;
    }

    public function forgotPasswordForm()
    {
        View::render('auth/forgot_password', [
            'csrf' => Csrf::generate(),
            'title' => 'Recuperar Senha'
        ]);
    }

    public function sendResetToken()
    {
        Csrf::validate($_POST['csrf'] ?? '');
        $email = trim($_POST['email'] ?? '');

        if (!$email) {
            Flash::set('error', 'Informe seu e-mail.');
            header("Location: /forgot-password");
            exit;
        }

        $token = $this->repo->createPasswordResetToken($email);

        if ($token) {
            $envPath = __DIR__ . '/../../env.php';

            if (file_exists($envPath)) {
                require_once $envPath;
            } else {
                error_log("Aviso: Arquivo env.php não encontrado em: " . $envPath);
            }

            $mail = new PHPMailer(true);
            try {
                $mail->isSMTP();
                $mail->Host = getenv('MAIL_HOST');
                $mail->SMTPAuth = true;
                $mail->Username = getenv('MAIL_USERNAME');
                $mail->Password = getenv('MAIL_PASSWORD');
                $mail->Port = (int) getenv('MAIL_PORT');
                $mail->CharSet = 'UTF-8';

                $mail->setFrom(getenv('MAIL_FROM_EMAIL'), getenv('MAIL_FROM_NAME'));
                $mail->addAddress($email);

                $mail->isHTML(true);
                $mail->Subject = 'Codigo de Recuperacao de Senha';
                $mail->Body = "Seu codigo de verificacao e: <b style='font-size: 24px;'>$token</b><br>Este código expira em 15 minutos.";

                $mail->send();

                $_SESSION['reset_email'] = $email;
                Flash::set('success', 'Código enviado! Verifique seu e-mail.');
                header("Location: /verify-token");
                exit;
            } catch (Exception $e) {
                Flash::set('error', "Erro ao enviar e-mail. Tente novamente mais tarde.");
                error_log("Erro PHPMailer: " . $mail->ErrorInfo);
            }
        } else {
            Flash::set('error', 'E-mail não encontrado em nossa base.');
        }
        header("Location: /forgot-password");
        exit;
    }

    public function verifyTokenForm()
    {
        if (!isset($_SESSION['reset_email'])) {
            header("Location: /forgot-password");
            exit;
        }
        View::render('auth/verify_token', [
            'csrf' => Csrf::generate(),
            'title' => 'Verificar Código'
        ]);
    }

    public function verifyToken()
    {
        Csrf::validate($_POST['csrf'] ?? '');
        $token = trim($_POST['token'] ?? '');

        $email = $this->repo->validateToken($token);

        if ($email && $email === $_SESSION['reset_email']) {
            $_SESSION['token_verified'] = $token; // Autoriza a troca
            header("Location: /reset-password");
        } else {
            Flash::set('error', 'Código inválido ou expirado.');
            header("Location: /verify-token");
        }
        exit;
    }

    public function resetPasswordForm()
    {
        if (!isset($_SESSION['token_verified'])) {
            header("Location: /forgot-password");
            exit;
        }
        View::render('auth/reset_password', [
            'csrf' => Csrf::generate(),
            'title' => 'Nova Senha'
        ]);
    }

    public function resetPassword()
    {
        if (!isset($_SESSION['token_verified']))
            exit;

        $password = $_POST['password'] ?? '';
        $email = $_SESSION['reset_email'];
        $token = $_SESSION['token_verified'];

        $stmt = $this->repo->getByIdEmail($email);

        if ($this->repo->updatePassword($stmt->id, $password)) {
            $this->repo->deleteToken($token);
            unset($_SESSION['reset_email'], $_SESSION['token_verified']);

            Flash::set('success', 'Senha alterada com sucesso! Faça login.');
            header("Location: /login");
        } else {
            Flash::set('error', 'Erro ao resetar senha.');
            header("Location: /reset-password");
        }
        exit;
    }
}