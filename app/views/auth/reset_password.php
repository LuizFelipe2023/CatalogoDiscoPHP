<div class="row justify-content-center align-items-center" style="min-height: 70vh;">
    <div class="col-md-5 col-lg-4">
        
        <div class="text-center mb-4">
            <i class="bi bi-shield-lock text-primary display-4"></i>
            <h1 class="fw-bold h3 mt-3">Nova Senha</h1>
            <p class="text-muted">Crie uma nova senha de acesso para sua conta</p>
        </div>

        <div class="card shadow-sm border-0">
            <div class="card-body p-4">
                <form method="POST" action="/reset-password" id="resetForm">
                    <input type="hidden" name="csrf" value="<?= $csrf; ?>">

                    <div class="mb-3">
                        <label class="form-label small fw-bold">Nova Senha</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0 text-muted">
                                <i class="bi bi-key"></i>
                            </span>
                            <input type="password" name="password" id="password" 
                                   class="form-control border-start-0 ps-0" 
                                   placeholder="••••••••" required minlength="6">
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label small fw-bold">Confirmar Senha</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0 text-muted">
                                <i class="bi bi-key-fill"></i>
                            </span>
                            <input type="password" id="confirm_password" 
                                   class="form-control border-start-0 ps-0" 
                                   placeholder="••••••••" required>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary w-100 fw-bold py-2 shadow-sm">
                        Atualizar Senha
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    document.getElementById('resetForm').onsubmit = function(e) {
        const pass = document.getElementById('password').value;
        const confirm = document.getElementById('confirm_password').value;
        if (pass !== confirm) {
            e.preventDefault();
            alert('As senhas não coincidem!');
        }
    };
</script>