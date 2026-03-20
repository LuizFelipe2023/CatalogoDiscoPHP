<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../app/core/Router.php';
require_once __DIR__ . '/../app/core/Auth.php';

// Apenas os requires aqui, sem o "new" no topo para o UserController e DiscoController
require_once __DIR__ . '/../app/controllers/DiscoController.php';
require_once __DIR__ . '/../app/controllers/AuthController.php';
require_once __DIR__ . '/../app/controllers/UserController.php'; 

// O AuthController pode ficar no topo pois ele gerencia rotas públicas
$auth = new AuthController();

/*
|--------------------------------------------------------------------------
| ROTAS PÚBLICAS
|--------------------------------------------------------------------------
*/
Router::route('GET', '/login', [$auth, 'loginForm']);
Router::route('POST', '/login', [$auth, 'login']);
Router::route('GET', '/register', [$auth, 'registerForm']);
Router::route('POST', '/register', [$auth, 'register']);
Router::route('GET', '/logout', [$auth, 'logout']);

/*
|--------------------------------------------------------------------------
| ROTAS DE RECUPERAÇÃO DE SENHA
|--------------------------------------------------------------------------
*/
Router::route('GET', '/forgot-password', [$auth, 'forgotPasswordForm']);
Router::route('POST', '/forgot-password', [$auth, 'sendResetToken']);

Router::route('GET', '/verify-token', [$auth, 'verifyTokenForm']);
Router::route('POST', '/verify-token', [$auth, 'verifyToken']);

Router::route('GET', '/reset-password', [$auth, 'resetPasswordForm']);
Router::route('POST', '/reset-password', [$auth, 'resetPassword']);

/*
|--------------------------------------------------------------------------
| ROTAS PROTEGIDAS (DISCOS)
|--------------------------------------------------------------------------
*/
// Usamos uma função anônima para instanciar o controller só quando a rota bater
Router::route('GET', '/', function() { (new DiscoController())->index(); }, true);
Router::route('GET', '/colecao', function() { (new DiscoController())->index(); }, true);
Router::route('GET', '/create', function() { (new DiscoController())->create(); }, true);
Router::route('POST', '/store', function() { (new DiscoController())->store(); }, true);
Router::match('GET', '#^/edit/(\d+)$#', function($id) { (new DiscoController())->edit($id); }, true);
Router::match('GET', '#^/disco/(\d+)$#', function($id) { (new DiscoController())->show($id); }, true);
Router::route('POST', '/update', function() { (new DiscoController())->update(); }, true);
Router::route('POST', '/delete', function() { (new DiscoController())->delete(); }, true);

/*
|--------------------------------------------------------------------------
| ROTAS ADMINISTRATIVAS (USUÁRIOS)
|--------------------------------------------------------------------------
*/

Router::route('GET', '/users', function() { (new UserController())->index(); }, true, true); 
Router::route('GET', '/users/create', function() { (new UserController())->create(); }, true, true); 
Router::route('POST', '/users/store', function() { (new UserController())->store(); }, true, true); 
Router::match('GET', '#^/users/edit/(\d+)$#', function($id) { (new UserController())->edit($id); }, true, true); 
Router::route('POST', '/users/update', function() { (new UserController())->update(); }, true, true); 
Router::route('POST', '/users/delete', function() { (new UserController())->delete(); }, true, true);

/*
|--------------------------------------------------------------------------
| PERFIL PESSOAL
|--------------------------------------------------------------------------
*/
Router::route('GET', '/profile', [$auth, 'profile'], true);     
Router::route('POST', '/profile', [$auth, 'updateProfile'], true); 

/*
|--------------------------------------------------------------------------
| 404
|--------------------------------------------------------------------------
*/
http_response_code(404);
echo "<h1>404 - Página não encontrada</h1>";