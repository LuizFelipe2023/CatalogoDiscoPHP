<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="d-flex align-items-center mb-4 gap-2">
            <a href="/users" class="btn btn-outline-secondary btn-sm rounded-circle">
                <i class="bi bi-arrow-left"></i>
            </a>
            <h1 class="h3 mb-0 fw-bold">Novo Usuário</h1>
        </div>

        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-body p-4">
                <form action="/users/store" method="POST"> 
                    <input type="hidden" name="csrf" value="<?= $csrf ?>">

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Nome Completo</label>
                        <input type="text" name="nome" class="form-control" placeholder="Ex: João Silva" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">E-mail</label>
                        <input type="email" name="email" class="form-control" placeholder="joao@email.com" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Senha Inicial</label>
                        <input type="password" name="password" class="form-control" required minlength="6">
                        <div class="form-text">O usuário poderá alterar a senha posteriormente.</div>
                    </div>

                    <div class="mb-4">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" name="is_admin" id="isAdmin" value="1">
                            <label class="form-check-label fw-semibold" for="isAdmin">
                                Privilégios de Administrador
                            </label>
                            <div class="form-text">Permite gerenciar outros usuários e discos.</div>
                        </div>
                    </div>

                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary py-2 fw-bold shadow-sm">
                            <i class="bi bi-person-plus-fill me-1"></i> Criar Conta
                        </button>
                        <a href="/users" class="btn btn-link text-muted">Cancelar</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>