<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="<?= asset('images/cit.png') ?>" type="image/x-icon">
    <link rel="stylesheet" href="<?= asset('build/styles.css') ?>">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.9.1/font/bootstrap-icons.css">
    <title>Lista de Compras</title>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container-fluid">
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarToggler" aria-controls="navbarToggler" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <a class="navbar-brand" href="/<?= $_ENV['APP_NAME'] ?>/">
            <img src="<?= asset('./images/cit.png') ?>" width="35px'" alt="cit">
            Lista de Compras
        </a>
        <div class="collapse navbar-collapse" id="navbarToggler">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0" style="margin: 0;">
                <li class="nav-item">
                    <a class="nav-link" aria-current="page" href="/<?= $_ENV['APP_NAME'] ?>/"><i class="bi bi-house-fill me-2"></i>Inicio</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/<?= $_ENV['APP_NAME'] ?>/producto"><i class="bi bi-cart-check me-2"></i>Productos</a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<main class="container pt-5 mb-4" style="min-height: 85vh">
    <?php echo $contenido; ?>
</main>

<footer class="bg-dark text-white py-3">
    <div class="container text-center">
        <p class="mb-0">Lista de Compras &copy; <?= date('Y') ?></p>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/js/bootstrap.bundle.min.js"></script>
<script src="<?= asset('build/js/app.js') ?>"></script>
</body>
</html>