<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="fw-bold">📀 Meus Discos</h1>
    <a href="/create" class="btn btn-primary shadow-sm">
        <i class="bi bi-plus-lg"></i> Novo Disco
    </a>
</div>

<div class="card border-0 shadow-sm mb-4">
    <div class="card-body p-4">
        <form method="GET" action="/" class="row g-3 align-items-end">
            <div class="col-md-4">
                <label class="form-label fw-semibold">Buscar</label>
                <input type="text" name="q" class="form-control" placeholder="Nome, artista ou gênero..."
                    value="<?= htmlspecialchars($filters['q'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
            </div>

            <div class="col-md-2">
                <label class="form-label fw-semibold">Status</label>
                <select name="status" class="form-select">
                    <option value="" <?= empty($filters['status']) ? 'selected' : ''; ?>>Todos</option>
                    <?php foreach (($statusesOptions ?? []) as $s): ?>
                        <option value="<?= $s ?>" <?= ($filters['status'] ?? null) === $s ? 'selected' : ''; ?>>
                            <?= $s ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="col-md-3">
                <label class="form-label fw-semibold">Gênero</label>
                <select name="genero" class="form-select">
                    <option value="" <?= empty($filters['genero']) ? 'selected' : ''; ?>>Todos</option>
                    <?php foreach (($generosOptions ?? []) as $g): ?>
                        <option value="<?= $g ?>" <?= ($filters['genero'] ?? null) === $g ? 'selected' : ''; ?>>
                            <?= $g ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="col-md-2">
                <label class="form-label fw-semibold">Formato</label>
                <select name="formato" class="form-select">
                    <option value="" <?= empty($filters['formato']) ? 'selected' : ''; ?>>Todos</option>
                    <?php foreach (($formatosOptions ?? []) as $f): ?>
                        <option value="<?= $f ?>" <?= ($filters['formato'] ?? null) === $f ? 'selected' : ''; ?>>
                            <?= $f ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="col-md-2">
                <label class="form-label fw-semibold">Avaliação (mín.)</label>
                <input type="number" step="0.1" min="0" max="10" name="minAvaliacao" class="form-control"
                    placeholder="0.0"
                    value="<?= htmlspecialchars((string) ($filters['minAvaliacao'] ?? ''), ENT_QUOTES, 'UTF-8'); ?>">
            </div>

            <div class="col-md-2">
                <label class="form-label fw-semibold">Avaliação (máx.)</label>
                <input type="number" step="0.1" min="0" max="10" name="maxAvaliacao" class="form-control"
                    placeholder="10.0"
                    value="<?= htmlspecialchars((string) ($filters['maxAvaliacao'] ?? ''), ENT_QUOTES, 'UTF-8'); ?>">
            </div>

            <div class="col-md-2">
                <label class="form-label fw-semibold">Ordenar</label>
                <select name="sort" class="form-select">
                    <?php foreach (($sortOptions ?? []) as $key => $label): ?>
                        <option value="<?= $key ?>" <?= ($filters['sort'] ?? 'nome_asc') === $key ? 'selected' : ''; ?>>
                            <?= htmlspecialchars($label, ENT_QUOTES, 'UTF-8'); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="col-12 d-flex justify-content-end gap-2">
                <a href="/" class="btn btn-outline-secondary px-4">Limpar</a>
                <button type="submit" class="btn btn-primary px-4 shadow-sm">
                    Aplicar
                </button>
            </div>
        </form>
    </div>
</div>

<?php if (empty($discos)): ?>
    <div class="alert alert-light border text-center py-5 shadow-sm rounded-4">
        <i class="bi bi-disc text-muted display-1 opacity-25 d-block mb-3"></i>
        <p class="text-muted mb-0">
            Nenhum disco encontrado<?php
            $activeFilters =
                !empty($filters['q'] ?? null) ||
                !empty($filters['status'] ?? null) ||
                !empty($filters['genero'] ?? null) ||
                !empty($filters['formato'] ?? null) ||
                (!isset($filters['minAvaliacao']) ? false : $filters['minAvaliacao'] !== null) ||
                (!isset($filters['maxAvaliacao']) ? false : $filters['maxAvaliacao'] !== null);
            echo $activeFilters ? ' com esses filtros.' : ' cadastrado na sua coleção.';
            ?>
        </p>
        <a href="/create" class="btn btn-link text-primary mt-2 text-decoration-none">Começar agora &rarr;</a>
    </div>
<?php else: ?>

    <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
        <?php foreach ($discos as $d): ?>
            <div class="col">
                <div class="card h-100 border-0 shadow-sm overflow-hidden disco-card">
                    <a href="/disco/<?= (int) $d->id; ?>" class="disco-cover-link">
                        <div class="disco-cover position-relative" style="height: 250px;">
                            <?php if (!empty($d->capa)): ?>
                                <img src="/uploads/<?= $d->capa; ?>" class="w-100 h-100 object-fit-cover"
                                    alt="<?= htmlspecialchars($d->nome ?? ''); ?>" loading="lazy">
                            <?php else: ?>
                                <div class="no-cover d-flex align-items-center justify-content-center bg-dark h-100">
                                    <i class="bi bi-disc fs-1 text-white opacity-25"></i>
                                </div>
                            <?php endif; ?>

                            <div class="overlay p-3 d-flex flex-column justify-content-end position-absolute top-0 start-0 w-100 h-100"
                                style="background: linear-gradient(transparent, rgba(0,0,0,0.8));">
                                <h5 class="fw-bold text-white mb-1 text-truncate">
                                    <?= htmlspecialchars($d->nome ?? '') ?>
                                </h5>
                                <p class="text-white-50 small mb-2">
                                    <?= htmlspecialchars($d->artista ?? '') ?>
                                </p>
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="badge bg-light text-dark small">
                                        <?= htmlspecialchars($d->genero ?? '') ?>
                                    </span>
                                    <?php if (!empty($d->avaliacao)): ?>
                                        <span class="text-warning small fw-bold">⭐
                                            <?= $d->avaliacao ?>
                                        </span>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </a>

                    <div class="card-body bg-white p-3">
                        <div class="mb-2">
                            <a href="/disco/<?= (int) $d->id; ?>"
                                class="btn btn-dark w-100 fw-bold shadow-sm d-flex align-items-center justify-content-center gap-2">
                                <i class="bi bi-eye-fill"></i> Ver Detalhes
                            </a>
                        </div>

                        <div class="d-flex gap-2">
                            <a href="/edit/<?= $d->id; ?>"
                                class="btn btn-sm btn-outline-warning w-100 fw-semibold d-flex align-items-center justify-content-center gap-1">
                                <i class="bi bi-pencil-square"></i> Editar
                            </a>
                            <button type="button"
                                class="btn btn-sm btn-outline-danger w-100 fw-semibold d-flex align-items-center justify-content-center gap-1"
                                data-bs-toggle="modal" data-bs-target="#deleteModal" data-id="<?= $d->id; ?>"
                                data-nome="<?= htmlspecialchars($d->nome ?? ''); ?>">
                                <i class="bi bi-trash"></i> Excluir
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <?php if (!empty($pagination['totalPages']) && (int) $pagination['totalPages'] > 1): ?>
        <nav class="mt-5" aria-label="Paginação">
            <ul class="pagination justify-content-center">
                <?php
                $page = (int) ($pagination['page'] ?? 1);
                $totalPages = (int) ($pagination['totalPages'] ?? 1);
                $queryParams = $queryParams ?? [];
                $prevPage = $page - 1;
                $nextPage = $page + 1;
                ?>

                <li class="page-item <?= $page <= 1 ? 'disabled' : '' ?>">
                    <?php if ($page <= 1): ?>
                        <span class="page-link" aria-hidden="true">Anterior</span>
                    <?php else: ?>
                        <a class="page-link" href="/?<?= http_build_query(array_merge($queryParams, ['page' => $prevPage])); ?>"
                            aria-label="Anterior">
                            Anterior
                        </a>
                    <?php endif; ?>
                </li>

                <?php
                $start = max(1, $page - 2);
                $end = min($totalPages, $page + 2);
                for ($p = $start; $p <= $end; $p++):
                    ?>
                    <li class="page-item <?= $p === $page ? 'active' : '' ?>">
                        <a class="page-link" href="/?<?= http_build_query(array_merge($queryParams, ['page' => $p])); ?>">
                            <?= $p ?>
                        </a>
                    </li>
                <?php endfor; ?>

                <li class="page-item <?= $page >= $totalPages ? 'disabled' : '' ?>">
                    <?php if ($page >= $totalPages): ?>
                        <span class="page-link" aria-hidden="true">Próxima</span>
                    <?php else: ?>
                        <a class="page-link" href="/?<?= http_build_query(array_merge($queryParams, ['page' => $nextPage])); ?>"
                            aria-label="Próxima">
                            Próxima
                        </a>
                    <?php endif; ?>
                </li>
            </ul>
        </nav>
    <?php endif; ?>

<?php endif; ?>

<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-danger text-white border-0">
                <h5 class="modal-title fw-bold" id="deleteModalLabel">Confirmar Exclusão</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>
            <div class="modal-body p-4 text-center">
                <div class="mb-3">
                    <i class="bi bi-exclamation-triangle text-danger display-4"></i>
                </div>
                <p class="text-muted mb-1">Tem certeza que deseja excluir o disco:</p>
                <h4 id="modalDiscoNome" class="fw-bold text-dark"></h4>
                <p class="small text-danger mt-3">
                    <i class="bi bi-info-circle"></i> Esta ação não pode ser desfeita.
                </p>
            </div>
            <div class="modal-footer border-0 justify-content-center pb-4">
                <button type="button" class="btn btn-light px-4 fw-semibold" data-bs-dismiss="modal">Cancelar</button>
                <form method="POST" action="/delete">
                    <input type="hidden" name="id" id="modalDiscoId">
                    <input type="hidden" name="csrf" value="<?= htmlspecialchars($csrf ?? '', ENT_QUOTES, 'UTF-8'); ?>">
                    <button type="submit" class="btn btn-danger px-4 fw-bold shadow-sm">Excluir Agora</button>
                </form>
            </div>
        </div>
    </div>
</div>

<link rel="stylesheet" href="/assets/css/index.css">
<script src="/assets/js/deleteModal.js"></script>