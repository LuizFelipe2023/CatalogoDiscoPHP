<?php
require_once __DIR__ . '/../../core/Flash.php';
$flash = Flash::get();
?>

<!DOCTYPE html>
<html lang="pt-br" class="h-100">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Catálogo de Discos' ?> | VinylCatalog</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="/assets/bootstrap/bootstrap.min.css" rel="stylesheet">
    
    <link rel="stylesheet" href="/assets/css/main.css">
</head>

<body id="top" class="d-flex flex-column h-100 bg-light">

<?php require __DIR__ . '/../partials/navbar.php'; ?>

<main class="flex-shrink-0">
    <div class="container py-4 main-content">
        
        <?php if ($flash): ?>
            <div class="alert alert-<?= ($flash['type'] === 'success') ? 'success' : 'danger' ?> alert-dismissible fade show shadow-sm border-0 mb-4" role="alert">
                <div class="d-flex align-items-center">
                    <i class="bi <?= ($flash['type'] === 'success') ? 'bi-check-circle-fill' : 'bi-exclamation-triangle-fill' ?> fs-5 me-2"></i>
                    <div><?= htmlspecialchars($flash['message']) ?></div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <section class="content-wrapper">
            <?= $content ?>
        </section>
        
    </div>
</main>

<?php require __DIR__ . '/../partials/footer.php'; ?>

<script src="/assets/bootstrap/bootstrap.bundle.min.js"></script>
<script src="/assets/js/alert.js"></script>
<script src="/assets/js/deleteModal.js"></script>


</body>
</html>