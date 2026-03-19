<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6">
            
            <div class="card border-0 shadow-lg rounded-4 overflow-hidden">
                <div class="card-header bg-dark py-4 text-center border-0">
                    <div class="position-relative d-inline-block">
                        <i class="bi bi-person-circle text-primary display-1"></i>
                        <span class="position-absolute bottom-0 end-0 bg-success border border-white border-3 rounded-circle p-2 shadow-sm"></span>
                    </div>
                    <h3 class="text-white mt-3 fw-bold mb-0">Meu Perfil</h3>
                    <p class="text-white-50 small">Gerencie suas informações de colecionador</p>
                </div>

                <div class="card-body p-4 p-md-5 bg-white">
                    <form action="/profile" method="POST">
                        <input type="hidden" name="csrf" value="<?= $csrf ?>">

                        <div class="mb-4">
                            <label class="form-label fw-bold text-dark small text-uppercase tracking-wider">Nome Completo</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0 text-muted"><i class="bi bi-person"></i></span>
                                <input type="text" name="nome" 
                                       class="form-control form-control-lg bg-light border-start-0 fs-6" 
                                       value="<?= htmlspecialchars($user['nome']) ?>" 
                                       placeholder="Seu nome" required>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-bold text-dark small text-uppercase tracking-wider">E-mail de Acesso</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0 text-muted"><i class="bi bi-envelope"></i></span>
                                <input type="email" name="email" 
                                       class="form-control form-control-lg bg-light border-start-0 fs-6" 
                                       value="<?= htmlspecialchars($user['email']) ?>" 
                                       placeholder="seu@email.com" required>
                            </div>
                            <div class="form-text text-muted small mt-2">
                                <i class="bi bi-info-circle me-1"></i> Este e-mail é usado para seu login.
                            </div>
                        </div>

                        <hr class="my-4 opacity-10">

                        <div class="d-grid gap-3">
                            <button type="submit" class="btn btn-primary btn-lg fw-bold shadow-sm py-3">
                                <i class="bi bi-check2-circle me-2"></i>Salvar Alterações
                            </button>
                            <a href="/" class="btn btn-outline-secondary py-2 border-0 fw-semibold">
                                Voltar para o Início
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            <div class="card mt-4 border-0 shadow-sm rounded-4 bg-light">
                <div class="card-body d-flex justify-content-between align-items-center py-3">
                    <div>
                        <span class="d-block fw-bold text-dark small uppercase">Segurança</span>
                        <span class="text-muted small">Deseja encerrar sua sessão atual?</span>
                    </div>
                    <a href="/logout" class="btn btn-sm btn-danger px-3 rounded-pill fw-bold">
                        Sair da Conta
                    </a>
                </div>
            </div>

        </div>
    </div>
</div>

<link rel="stylesheet" href="/assets/css/profile.css">