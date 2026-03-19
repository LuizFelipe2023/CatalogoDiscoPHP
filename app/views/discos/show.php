<div class="row justify-content-center">
    <div class="col-md-10 col-lg-8">
        <div class="d-flex align-items-center mb-4 gap-3">
            <a href="/" class="btn btn-outline-secondary btn-sm rounded-circle shadow-sm" title="Voltar para a lista">
                <i class="bi bi-arrow-left"></i>
            </a>
            <div>
                <h1 class="h3 mb-0 fw-bold">Detalhes do Disco</h1>
                <p class="text-muted mb-0 small">ID: #<?= (int)($disco->id ?? 0) ?></p>
            </div>
        </div>

        <div class="card shadow-sm border-0 rounded-4 overflow-hidden">
            <div class="row g-0">
                <div class="col-12 col-md-5">
                    <div class="bg-dark h-100 d-flex align-items-center justify-content-center" style="min-height: 350px;">
                        <?php if (!empty($disco->capa)): ?>
                            <img src="/uploads/<?= $disco->capa; ?>" 
                                 class="w-100 h-100 object-fit-cover" 
                                 alt="<?= htmlspecialchars($disco->nome ?? 'Capa'); ?>">
                        <?php else: ?>
                            <div class="text-center">
                                <i class="bi bi-disc text-white opacity-25 display-1"></i>
                                <p class="text-white opacity-50 small mt-2">Sem capa cadastrada</p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="col-12 col-md-7">
                    <div class="card-body p-4 p-lg-5">
                        <div class="mb-4">
                            <span class="badge bg-primary-subtle text-primary border border-primary-subtle mb-2">
                                <?= htmlspecialchars($disco->status ?? 'Disponível') ?>
                            </span>
                            <h2 class="h2 fw-bold mb-1"><?= htmlspecialchars($disco->nome ?? '') ?></h2>
                            <h4 class="text-muted fw-normal"><?= htmlspecialchars($disco->artista ?? '') ?></h4>
                        </div>

                        <div class="row g-4 text-break">
                            <div class="col-6">
                                <label class="small text-muted d-block">Gênero</label>
                                <span class="fw-semibold text-dark"><?= htmlspecialchars($disco->genero ?? 'Não informado') ?></span>
                            </div>
                            <div class="col-6">
                                <label class="small text-muted d-block">Formato</label>
                                <span class="fw-semibold text-dark"><?= htmlspecialchars($disco->formato ?? 'Não informado') ?></span>
                            </div>
                            <div class="col-12">
                                <label class="small text-muted d-block">Avaliação Pessoal</label>
                                <div class="fs-5">
                                    <?php if (!empty($disco->avaliacao) && $disco->avaliacao > 0): ?>
                                        <span class="text-warning fw-bold">⭐ <?= number_format((float)$disco->avaliacao, 1) ?></span>
                                        <small class="text-muted">/ 10</small>
                                    <?php else: ?>
                                        <span class="text-muted small italic">Ainda não avaliado</span>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>

                        <hr class="my-4 opacity-50">

                        <div class="d-flex gap-2">
                            <a href="/edit/<?= (int)$disco->id; ?>" class="btn btn-warning px-4 fw-bold shadow-sm">
                                <i class="bi bi-pencil-square me-2"></i>Editar
                            </a>
                            
                            <button type="button" class="btn btn-outline-danger px-4 fw-bold" data-bs-toggle="modal" data-bs-target="#deleteModal">
                                <i class="bi bi-trash me-2"></i>Excluir
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-danger text-white border-0">
                <h5 class="modal-title fw-bold" id="deleteModalLabel">Confirmar Exclusão</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4 text-center">
                <div class="mb-3">
                    <i class="bi bi-exclamation-triangle text-danger display-4"></i>
                </div>
                <p class="text-muted mb-1">Você está prestes a excluir permanentemente:</p>
                <h4 class="fw-bold text-dark"><?= htmlspecialchars($disco->nome ?? 'Este disco') ?></h4>
                <p class="small text-danger mt-3">
                    <i class="bi bi-info-circle"></i> Esta ação não pode ser desfeita e removerá o item da sua coleção.
                </p>
            </div>
            <div class="modal-footer border-0 justify-content-center pb-4">
                <button type="button" class="btn btn-light px-4 fw-semibold" data-bs-dismiss="modal">Cancelar</button>
                <form method="POST" action="/delete">
                    <input type="hidden" name="id" value="<?= (int)$disco->id; ?>">
                    <input type="hidden" name="csrf" value="<?= htmlspecialchars($csrf ?? ''); ?>">
                    <button type="submit" class="btn btn-danger px-4 fw-bold shadow-sm">Sim, Excluir Agora</button>
                </form>
            </div>
        </div>
    </div>
</div>