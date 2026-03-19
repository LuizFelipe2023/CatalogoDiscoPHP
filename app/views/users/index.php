<?php 
$usuarios = $usuarios ?? [];
$csrf = $csrf ?? '';
$meuId = (int)($_SESSION['user']['id'] ?? 0);
?>

<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="fw-bold mb-1">👥 Gerenciar Usuários</h1>
            <p class="text-muted small mb-0">Administre as contas de acesso ao sistema.</p>
        </div>
        <a href="/users/create" class="btn btn-primary px-4 shadow-sm fw-bold">
            <i class="bi bi-person-plus me-1"></i> Novo Usuário
        </a>
    </div>

    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="ps-4 py-3 text-uppercase small fw-bold text-muted">ID</th>
                        <th class="py-3 text-uppercase small fw-bold text-muted">Nome</th>
                        <th class="py-3 text-uppercase small fw-bold text-muted">E-mail</th>
                        <th class="py-3 text-uppercase small fw-bold text-muted text-center">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($usuarios)): ?>
                        <tr>
                            <td colspan="4" class="text-center py-5 text-muted">
                                <i class="bi bi-people display-4 d-block mb-2 opacity-25"></i>
                                Nenhum usuário encontrado.
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($usuarios as $user): ?>
                            <tr>
                                <td class="ps-4 text-muted small">#<?= (int)$user['id'] ?></td>
                                <td>
                                    <span class="fw-semibold text-dark"><?= htmlspecialchars($user['nome']) ?></span>
                                    <?php if ((int)$user['id'] === $meuId): ?>
                                        <span class="badge bg-info-subtle text-info ms-2 small">Você</span>
                                    <?php endif; ?>
                                    <?php if (isset($user['is_admin']) && $user['is_admin']): ?>
                                        <span class="badge bg-secondary-subtle text-secondary ms-1 small" title="Administrador">
                                            <i class="bi bi-shield-check"></i>
                                        </span>
                                    <?php endif; ?>
                                </td>
                                <td><?= htmlspecialchars($user['email']) ?></td>
                                <td class="text-center">
                                    <div class="d-flex justify-content-center gap-2">
                                        <a href="/users/edit/<?= (int)$user['id'] ?>" 
                                           class="btn btn-sm btn-outline-primary border-0 rounded-3 px-3" 
                                           title="Editar Usuário">
                                            <i class="bi bi-pencil-square"></i>
                                        </a>

                                        <?php if ((int)$user['id'] !== $meuId): ?>
                                            <button type="button" 
                                                    class="btn btn-sm btn-outline-danger border-0 rounded-3 px-3" 
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#deleteUserModal" 
                                                    data-id="<?= (int)$user['id'] ?>"
                                                    data-nome="<?= htmlspecialchars($user['nome']) ?>"
                                                    title="Excluir Usuário">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        <?php else: ?>
                                            <button class="btn btn-sm btn-light border-0 rounded-3 px-3 disabled" 
                                                    title="Você não pode excluir sua própria conta">
                                                <i class="bi bi-shield-lock"></i>
                                            </button>
                                        <?php endif; ?>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="modal fade" id="deleteUserModal" tabindex="-1" aria-labelledby="deleteUserModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4">
            <div class="modal-header bg-danger text-white border-0 py-3">
                <h5 class="modal-title fw-bold" id="deleteUserModalLabel">Confirmar Exclusão</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4 text-center">
                <div class="mb-3">
                    <i class="bi bi-person-x-fill text-danger display-1"></i>
                </div>
                <p class="text-muted mb-1">Você tem certeza que deseja remover o usuário:</p>
                <h4 id="modalUserName" class="fw-bold text-dark mb-3"></h4>
                <div class="alert alert-warning small border-0 py-2">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i>
                    Atenção: Ações de exclusão não podem ser desfeitas.
                </div>
            </div>
            <div class="modal-footer border-0 justify-content-center pb-4">
                <button type="button" class="btn btn-light px-4 fw-semibold" data-bs-dismiss="modal">Cancelar</button>
                <form method="POST" action="/users/delete" class="d-inline">
                    <input type="hidden" name="id" id="modalUserId">
                    <input type="hidden" name="csrf" value="<?= htmlspecialchars($csrf) ?>">
                    <button type="submit" class="btn btn-danger px-4 fw-bold shadow-sm">Confirmar Exclusão</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script src="/assets/js/deleteUser.js"></script>