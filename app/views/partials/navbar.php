<?php
require_once __DIR__ . '/../../core/Auth.php';
$user = Auth::user();
$current_page = $_SERVER['REQUEST_URI'];
?>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow-sm mb-4">
    <div class="container">
        <a class="navbar-brand d-flex align-items-center fw-bold" href="/">
            <i class="bi bi-disc-fill me-2 text-primary"></i>
            <span>VINYL<span class="text-primary">CATALOG</span></span>
        </a>

        <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link <?= $current_page == '/' ? 'active' : '' ?>" href="/">
                        <i class="bi bi-house-door me-1"></i> Início
                    </a>
                </li>
                
                <?php if ($user): ?>
                    <li class="nav-item">
                        <a class="nav-link <?= ($current_page == '/colecao') ? 'active' : '' ?>" href="/colecao">
                            <i class="bi bi-collection-play me-1"></i> Minha Coleção
                        </a>
                    </li>

                    <?php if (Auth::isAdmin()): ?>
                    <li class="nav-item">
                        <a class="nav-link <?= strpos($current_page, '/users') !== false ? 'active' : '' ?>" href="/users">
                            <i class="bi bi-people-fill me-1"></i> Gerenciar Usuários
                        </a>
                    </li>
                    <?php endif; ?>
                <?php endif; ?>
            </ul>

            <div class="d-flex align-items-center gap-3">
                <?php if ($user): ?>
                    <a href="/create" class="btn btn-primary btn-sm d-flex align-items-center shadow-sm px-3 rounded-pill">
                        <i class="bi bi-plus-lg me-1"></i> Novo Disco
                    </a>

                    <div class="dropdown">
                        <button class="btn btn-outline-light btn-sm dropdown-toggle d-flex align-items-center gap-2 px-3 rounded-pill"
                            type="button" data-bs-toggle="dropdown">
                            <i class="bi bi-person-circle text-primary"></i>
                            <span class="d-none d-sm-inline"><?= htmlspecialchars($user['nome']); ?></span>
                            <?php if (Auth::isAdmin()): ?>
                                <span class="badge bg-primary" style="font-size: 0.6rem;">ADMIN</span>
                            <?php endif; ?>
                        </button>
                        
                        <ul class="dropdown-menu dropdown-menu-end shadow border-0 mt-2">
                            <li>
                                <div class="px-3 py-2">
                                    <p class="mb-0 small fw-bold text-dark"><?= htmlspecialchars($user['nome']); ?></p>
                                    <p class="mb-0 small text-muted text-truncate" style="max-width: 150px;">
                                        <?= htmlspecialchars($user['email']); ?>
                                    </p>
                                </div>
                            </li>
                            <li><hr class="dropdown-divider"></li>
                            
                            <li>
                                <a class="dropdown-item d-flex align-items-center gap-2 <?= $current_page == '/profile' ? 'active text-white' : '' ?>" href="/profile">
                                    <i class="bi bi-person-gear"></i> Meu Perfil
                                </a>
                            </li>

                            <li>
                                <a class="dropdown-item d-flex align-items-center gap-2 text-danger" href="/logout">
                                    <i class="bi bi-box-arrow-right"></i> Sair
                                </a>
                            </li>
                        </ul>
                    </div>

                <?php else: ?>
                    <div class="btn-group shadow-sm">
                        <a href="/login" class="btn btn-outline-light btn-sm px-3 <?= $current_page == '/login' ? 'active' : '' ?>">Login</a>
                        <a href="/register" class="btn btn-primary btn-sm px-3 <?= $current_page == '/register' ? 'active' : '' ?>">Cadastrar</a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</nav>