<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="d-flex align-items-center mb-4">
            <a href="/" class="btn btn-outline-secondary btn-sm rounded-circle me-3">
                <i class="bi bi-arrow-left"></i>
            </a>
            <div>
                <h1 class="h3 mb-0 fw-bold">✏️ Editar Registro</h1>
                <p class="text-muted mb-0 small">ID do Disco: #<?= $disco->id; ?></p>
            </div>
        </div>

        <div class="card shadow-sm border-0">
            <div class="card-body p-4">

                <form method="POST" action="/update" enctype="multipart/form-data">
                    <input type="hidden" name="id" value="<?= $disco->id; ?>">
                    <input type="hidden" name="csrf" value="<?= $csrf; ?>">

                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <label class="form-label fw-bold">Nome do Álbum</label>
                            <input name="nome" class="form-control form-control-lg" 
                                   value="<?= htmlspecialchars($disco->nome); ?>" required>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Artista</label>
                            <input name="artista" class="form-control" 
                                   value="<?= htmlspecialchars($disco->artista); ?>" required>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Gênero</label>
                            <select name="genero" class="form-select" required>
                                <?php $generos = ['Rock', 'Jazz', 'Pop', 'MPB', 'Eletrônica', 'Clássica', 'Outro']; ?>
                                <?php foreach($generos as $g): ?>
                                    <option value="<?= $g ?>" <?= $disco->genero == $g ? 'selected' : '' ?>>
                                        <?= $g ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="col-md-4 mb-3">
                            <label class="form-label fw-semibold">Avaliação (0-10)</label>
                            <div class="input-group">
                                <input type="number" step="0.1" min="0" max="10" name="avaliacao" 
                                       class="form-control" value="<?= $disco->avaliacao; ?>">
                                <span class="input-group-text">
                                    <i class="bi bi-star-fill text-warning"></i>
                                </span>
                            </div>
                        </div>

                        <div class="col-md-4 mb-3">
                            <label class="form-label fw-semibold">Formato</label>
                            <select name="formato" class="form-select" required>
                                <?php $formatos = ['Vinil', 'CD', 'Cassete', 'Digital']; ?>
                                <?php foreach($formatos as $f): ?>
                                    <option value="<?= $f ?>" <?= $disco->formato == $f ? 'selected' : '' ?>>
                                        <?= $f ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="col-md-4 mb-4">
                            <label class="form-label fw-semibold">Status</label>
                            <select name="status" class="form-select" required>
                                <option value="Na Coleção" <?= $disco->status == 'Na Coleção' ? 'selected' : '' ?>>Na Coleção</option>
                                <option value="Desejado" <?= $disco->status == 'Desejado' ? 'selected' : '' ?>>Desejado</option>
                                <option value="Emprestado" <?= $disco->status == 'Emprestado' ? 'selected' : '' ?>>Emprestado</option>
                            </select>
                        </div>
                    </div>

                   
                    <div class="mb-4">
                        <label class="form-label fw-semibold">Capa do Disco</label>

                      
                        <?php if (!empty($disco->capa)): ?>
                            <div class="mb-3">
                                <img 
                                    src="/uploads/<?= $disco->capa; ?>" 
                                    alt="<?= htmlspecialchars($disco->nome ?? 'Capa do disco'); ?>"
                                    style="max-width: 150px; border-radius:8px;"
                                    class="shadow-sm"
                                >
                            </div>
                        <?php endif; ?>

                        <input 
                            type="file" 
                            name="capa" 
                            class="form-control"
                            accept="image/*"
                            onchange="previewImage(event)"
                        >

                       
                        <div class="mt-3">
                            <img 
                                id="preview" 
                                src="" 
                                alt="Pré-visualização da capa do disco"
                                style="max-width: 150px; display:none; border-radius:8px;"
                                class="shadow-sm"
                            >
                        </div>

                        <div class="form-text">
                            Envie uma nova imagem para substituir a atual
                        </div>
                    </div>

                    <div class="d-flex justify-content-between align-items-center bg-light p-3 rounded">
                        <small class="text-muted">Certifique-se de salvar as alterações.</small>
                        <div class="d-flex gap-2">
                            <a href="/" class="btn btn-link text-secondary text-decoration-none">Descartar</a>
                            <button type="submit" class="btn btn-warning px-4 fw-bold">
                                <i class="bi bi-arrow-clockwise"></i> Atualizar Dados
                            </button>
                        </div>
                    </div>
                </form>

            </div>
        </div>
    </div>
</div>

<script>
function previewImage(event) {
    const input = event.target;
    const preview = document.getElementById('preview');

    if (input.files && input.files[0]) {
        preview.src = URL.createObjectURL(input.files[0]);
        preview.style.display = 'block';
    }
}
</script>