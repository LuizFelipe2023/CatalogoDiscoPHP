<div class="row justify-content-center align-items-center" style="min-height: 70vh;">
    <div class="col-md-5 col-lg-4">
        
        <div class="text-center mb-4">
            <i class="bi bi-patch-check text-primary display-4"></i>
            <h1 class="fw-bold h3 mt-3">Verifique seu E-mail</h1>
            <p class="text-muted">Digitie o código enviado para <br><strong><?= $_SESSION['reset_email'] ?></strong></p>
        </div>

        <div class="card shadow-sm border-0">
            <div class="card-body p-4">
                <form method="POST" action="/verify-token">
                    <input type="hidden" name="csrf" value="<?= $csrf; ?>">

                    <div class="mb-4 text-center">
                        <label class="form-label small fw-bold d-block mb-3">Código de 6 dígitos</label>
                        <input type="text" name="token" class="form-control form-control-lg text-center fw-bold border-2" 
                               maxlength="6" pattern="\d{6}" inputmode="numeric" 
                               placeholder="000000" style="letter-spacing: 0.5rem; font-size: 1.5rem;" required autofocus>
                    </div>

                    <button type="submit" class="btn btn-primary w-100 fw-bold py-2 shadow-sm">
                        Validar Código
                    </button>
                </form>
            </div>
        </div>

        <div class="text-center mt-4">
            <p class="text-muted small">
                Não recebeu o código? 
                <a href="/forgot-password" class="fw-bold text-decoration-none">Reenviar agora</a>
            </p>
        </div>
    </div>
</div>