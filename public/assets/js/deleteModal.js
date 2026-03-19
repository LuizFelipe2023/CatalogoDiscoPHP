document.addEventListener('DOMContentLoaded', function () {

    const deleteModal = document.getElementById('deleteModal');

    if (!deleteModal) return;

    deleteModal.addEventListener('show.bs.modal', function (event) {
        const button = event.relatedTarget;

        if (!button) return;

        const id = button.getAttribute('data-id');
        const nome = button.getAttribute('data-nome');

        document.getElementById('modalDiscoId').value = id;
        document.getElementById('modalDiscoNome').textContent = nome;
    });

});