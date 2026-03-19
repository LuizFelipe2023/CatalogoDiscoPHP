document.addEventListener('DOMContentLoaded', function () {
    const alerts = document.querySelectorAll('.alert-success, .alert-danger');

    alerts.forEach(alert => {
        const isSuccess = alert.classList.contains('alert-success');
        const timeoutMs = isSuccess ? 5000 : 9000;

        let timeoutId = null;

        const close = () => {
            const bsAlert = new bootstrap.Alert(alert);
            bsAlert.close();
        };

        const scheduleClose = () => {
            timeoutId = window.setTimeout(close, timeoutMs);
        };

        const clearClose = () => {
            if (timeoutId) window.clearTimeout(timeoutId);
            timeoutId = null;
        };

        // Pause auto-dismiss while the user is interacting with the alert.
        alert.addEventListener('mouseenter', clearClose);
        alert.addEventListener('mouseleave', scheduleClose);

        scheduleClose();
    });
});