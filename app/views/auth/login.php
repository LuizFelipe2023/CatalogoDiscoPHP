<div class="row justify-content-center align-items-center" style="min-height: 70vh;">
    <div class="col-md-5 col-lg-4">
        
        <div class="text-center mb-4">
            <i class="bi bi-shield-lock text-primary display-4"></i>
            <h1 class="fw-bold h3 mt-3">Bem-vindo de volta</h1>
            <p class="text-muted">Acesse sua coleção de discos</p>
        </div>

        <div class="card shadow-sm border-0">
            <div class="card-body p-4">
                <form method="POST" action="/login">
                    <input type="hidden" name="csrf" value="<?= $csrf; ?>">

                    <div class="mb-3">
                        <label class="form-label small fw-bold">E-mail</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0 text-muted">
                                <i class="bi bi-envelope"></i>
                            </span>
                            <input type="email" name="email" class="form-control border-start-0 ps-0" 
                                   placeholder="seu@email.com" required autofocus>
                        </div>
                    </div>

                    <div class="mb-3">
                        <div class="d-flex justify-content-between">
                            <label class="form-label small fw-bold">Senha</label>
                            <a href="/forgot-password" class="small text-decoration-none">Esqueceu a senha?</a>
                        </div>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0 text-muted">
                                <i class="bi bi-key"></i>
                            </span>
                            <input type="password" name="password" class="form-control border-start-0 ps-0" 
                                   placeholder="••••••••" required>
                        </div>
                    </div>

                    <div class="mb-4 form-check">
                        <input type="checkbox" class="form-check-input" id="remember" name="remember">
                        <label class="form-check-label small text-muted" for="remember">Lembrar de mim</label>
                    </div>

                    <button type="submit" class="btn btn-primary w-100 fw-bold py-2 shadow-sm">
                        Entrar na Conta
                    </button>
                </form>
            </div>
        </div>

        <div class="text-center mt-4">
            <p class="text-muted small">
                Ainda não tem uma conta? 
                <a href="/register" class="fw-bold text-decoration-none">Crie uma agora</a>
            </p>
        </div>

    </div>
</div>