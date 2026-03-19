(function () {
    document.addEventListener('DOMContentLoaded', function () {
        const modalElement = document.getElementById('deleteUserModal');
        if (!modalElement) return;

        modalElement.addEventListener('show.bs.modal', function (event) {
            const button = event.relatedTarget;
            const userId = button.getAttribute('data-id');
            const userName = button.getAttribute('data-nome');

            const inputId = modalElement.querySelector('#modalUserId');
            const textNome = modalElement.querySelector('#modalUserName');

            if (inputId) inputId.value = userId;
            if (textNome) textNome.textContent = userName;
        });
    });
})();