<div class="row justify-content-center align-items-center" style="min-height: 75vh;">
    <div class="col-md-6 col-lg-5">
        
        <div class="text-center mb-4">
            <i class="bi bi-person-plus text-success display-4"></i>
            <h1 class="fw-bold h3 mt-3">Crie sua conta</h1>
            <p class="text-muted">Comece a organizar sua coleção de vinis hoje mesmo</p>
        </div>

        <div class="card shadow-sm border-0">
            <div class="card-body p-4 p-md-5">
                <form method="POST" action="/register">
                    <input type="hidden" name="csrf" value="<?= $csrf; ?>">

                    <div class="mb-3">
                        <label class="form-label small fw-bold text-secondary">Nome Completo</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0 text-muted">
                                <i class="bi bi-person"></i>
                            </span>
                            <input type="text" name="nome" class="form-control border-start-0 ps-0" 
                                   placeholder="Como prefere ser chamado?" required autofocus>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-bold text-secondary">E-mail Profissional ou Pessoal</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0 text-muted">
                                <i class="bi bi-envelope"></i>
                            </span>
                            <input type="email" name="email" class="form-control border-start-0 ps-0" 
                                   placeholder="exemplo@email.com" required>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label small fw-bold text-secondary">Defina uma Senha</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0 text-muted">
                                <i class="bi bi-lock"></i>
                            </span>
                            <input type="password" name="password" class="form-control border-start-0 ps-0" 
                                   placeholder="Mínimo 8 caracteres" required>
                        </div>
                        <div class="form-text mt-2 small">
                            <i class="bi bi-info-circle me-1"></i> Use uma senha forte para proteger seus dados.
                        </div>
                    </div>

                    <button type="submit" class="btn btn-success w-100 fw-bold py-2 shadow-sm mb-3">
                        Finalizar Cadastro
                    </button>
                    
                    <a href="/login" class="btn btn-outline-secondary w-100 py-2 fw-semibold">
                        Já tenho uma conta
                    </a>
                </form>
            </div>
        </div>

        <div class="text-center mt-4">
            <p class="text-muted small">
                Ao registrar-se, você concorda com nossos 
                <a href="#" class="text-decoration-none">Termos de Uso</a>.
            </p>
        </div>

    </div>
</div>