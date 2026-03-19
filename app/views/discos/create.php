<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="d-flex align-items-center mb-4">
            <a href="/" class="btn btn-link text-decoration-none p-0 me-3">
                <i class="bi bi-arrow-left fs-4"></i>
            </a>
            <h1 class="h3 mb-0 fw-bold text-dark">➕ Adicionar Novo Disco</h1>
        </div>

        <div class="card shadow-sm border-0">
            <div class="card-body p-4">

                <!-- ⚠️ enctype obrigatório -->
                <form method="POST" action="/store" enctype="multipart/form-data">
                    <input type="hidden" name="csrf" value="<?= $csrf; ?>">

                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <label class="form-label fw-semibold">Nome do Álbum</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light">
                                    <i class="bi bi-disc"></i>
                                </span>
                                <input name="nome" class="form-control" placeholder="Ex: Dark Side of the Moon" required>
                            </div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Artista / Banda</label>
                            <input name="artista" class="form-control" placeholder="Ex: Pink Floyd" required>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Gênero</label>
                            <select name="genero" class="form-select" required>
                                <option value="" selected disabled>Selecione...</option>
                                <option value="Rock">Rock</option>
                                <option value="Jazz">Jazz</option>
                                <option value="Pop">Pop</option>
                                <option value="MPB">MPB</option>
                                <option value="Eletrônica">Eletrônica</option>
                                <option value="Outro">Outro</option>
                            </select>
                        </div>

                        <div class="col-md-4 mb-3">
                            <label class="form-label fw-semibold">Avaliação (0-10)</label>
                            <input type="number" step="0.1" min="0" max="10" name="avaliacao" class="form-control" placeholder="8.5">
                        </div>

                        <div class="col-md-4 mb-3">
                            <label class="form-label fw-semibold">Formato</label>
                            <select name="formato" class="form-select" required>
                                <option value="Vinil">Vinil</option>
                                <option value="CD">CD</option>
                                <option value="Cassete">Cassete</option>
                                <option value="Digital">Digital</option>
                            </select>
                        </div>

                        <div class="col-md-4 mb-4">
                            <label class="form-label fw-semibold">Status</label>
                            <select name="status" class="form-select" required>
                                <option value="Na Coleção">Na Coleção</option>
                                <option value="Desejado">Lista de Desejos</option>
                                <option value="Emprestado">Emprestado</option>
                            </select>
                        </div>
                    </div>

                    <!-- 🖼️ UPLOAD DE CAPA -->
                    <div class="mb-4">
                        <label class="form-label fw-semibold">Capa do Disco</label>

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
                            JPG, PNG ou WEBP (máx 2MB)
                        </div>
                    </div>

                    <hr class="my-4 opacity-25">

                    <div class="d-flex justify-content-end gap-2">
                        <a href="/" class="btn btn-light px-4">Cancelar</a>

                        <button type="submit" class="btn btn-primary px-4 shadow-sm">
                            <i class="bi bi-check-lg me-1"></i> Salvar Disco
                        </button>
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