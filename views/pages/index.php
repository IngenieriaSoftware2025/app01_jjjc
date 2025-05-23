<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Compras</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body {
            background-color:rgb(27, 155, 230);
        }
    </style>
</head>
<body>
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-body text-center p-5">
                    <h2 class="card-title mb-4">Bienvenido a la Lista de Compras</h2>
                    <p class="lead mb-4">Esta aplicación te ayudará a organizar tus compras semanales de forma ordenada y eficiente.</p>
                    <div class="d-grid gap-2 col-6 mx-auto">
                        <a href="/<?= $_ENV['APP_NAME'] ?>/producto" class="btn btn-primary btn-lg">
                            <i class="bi bi-cart-check"></i> Ver mi lista de compras
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>