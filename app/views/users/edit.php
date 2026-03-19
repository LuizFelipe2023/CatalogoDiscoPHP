<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="d-flex align-items-center mb-4 gap-2">
            <a href="/users" class="btn btn-outline-secondary btn-sm rounded-circle">
                <i class="bi bi-arrow-left"></i>
            </a>
            <h1 class="h3 mb-0 fw-bold">Editar Usuário</h1>
        </div>

        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-body p-4">
                <form action="/users/update" method="POST">
                    <input type="hidden" name="csrf" value="<?= $csrf ?>">
                    <input type="hidden" name="id" value="<?= $usuario->id ?>">

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Nome Completo</label>
                        <input type="text" name="nome" class="form-control" 
                               value="<?= htmlspecialchars($usuario->nome) ?>" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">E-mail</label>
                        <input type="email" name="email" class="form-control" 
                               value="<?= htmlspecialchars($usuario->email) ?>" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Nova Senha (Opcional)</label>
                        <input type="password" name="password" class="form-control" minlength="6">
                        <div class="form-text text-muted">
                            Deixe em branco para manter a senha atual.
                        </div>
                    </div>

                    <div class="mb-4">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" name="is_admin" id="isAdmin" value="1" 
                                   <?= $usuario->is_admin ? 'checked' : '' ?>>
                            <label class="form-check-label fw-semibold" for="isAdmin">
                                Privilégios de Administrador
                            </label>
                        </div>
                    </div>

                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary py-2 fw-bold shadow-sm">
                            <i class="bi bi-save me-1"></i> Salvar Alterações
                        </button>
                        <a href="/users" class="btn btn-link text-muted">Cancelar</a>
                    </div>
                </form>
            </div>
        </div>
        
        <?php if ($usuario->id === Auth::id()): ?>
            <div class="alert alert-info mt-3 border-0 shadow-sm small">
                <i class="bi bi-info-circle me-1"></i> 
                Você está editando seu próprio perfil. Cuidado ao remover seus privilégios de administrador!
            </div>
        <?php endif; ?>
    </div>
</div>