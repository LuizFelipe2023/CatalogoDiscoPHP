<div class="row justify-content-center align-items-center" style="min-height: 70vh;">
    <div class="col-md-5 col-lg-4">
        
        <div class="text-center mb-4">
            <i class="bi bi-envelope-at text-primary display-4"></i>
            <h1 class="fw-bold h3 mt-3">Recuperar Senha</h1>
            <p class="text-muted">Enviaremos um código de 6 dígitos para o seu e-mail</p>
        </div>

        <div class="card shadow-sm border-0">
            <div class="card-body p-4">
                <form method="POST" action="/forgot-password">
                    <input type="hidden" name="csrf" value="<?= $csrf; ?>">

                    <div class="mb-4">
                        <label class="form-label small fw-bold">E-mail cadastrado</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0 text-muted">
                                <i class="bi bi-envelope"></i>
                            </span>
                            <input type="email" name="email" class="form-control border-start-0 ps-0" 
                                   placeholder="seu@email.com" required autofocus>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary w-100 fw-bold py-2 shadow-sm">
                        Enviar Código de Verificação
                    </button>
                </form>
            </div>
        </div>

        <div class="text-center mt-4">
            <a href="/login" class="small text-decoration-none">
                <i class="bi bi-arrow-left me-1"></i> Voltar para o Login
            </a>
        </div>
    </div>
</div>