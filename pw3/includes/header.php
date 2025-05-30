<?php
if (!isset($_SESSION)) {
    session_start();
}

// Verificar acesso em páginas restritas
$paginasRestritas = ['cadastro.php', 'consulta.php', 'area_usuario.php'];
$paginaAtual = basename($_SERVER['PHP_SELF']);

if (in_array($paginaAtual, $paginasRestritas)) {
    if (!isset($_SESSION['usuario_logado'])) {
        header('Location: login.php?erro=acesso_negado');
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DriveX Motors</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <!-- SweetAlert2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/@sweetalert2/theme-bootstrap-4/bootstrap-4.css" rel="stylesheet">
    <!-- jQuery primeiro, depois Popper.js, depois Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.min.js"></script>
    <!-- jQuery Mask Plugin -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js"></script>
    <!-- SweetAlert2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- Seu CSS customizado -->
    <link rel="stylesheet" href="css/style.css">
    <!-- Main CSS -->
    <link rel="stylesheet" href="assets/css/main.css">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css"
        integrity="sha512-Fo3rlrZj/k7ujTnHg4CGR2D7kSs0v4LLanw2qksYuRlEzO+tcaEPQogQ0KaoGN26/zrn20ImR1DfuLWnOo7aBA=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-primary bg-white fixed-top">
        <div class="container">
            <a class="navbar-brand" href="index.php">

            </a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link <?php echo ($_SERVER['PHP_SELF'] == '/index.php' ? 'active' : ''); ?>"
                            href="index.php">Home</a>
                    </li>

                    <?php if (isset($_SESSION['usuario_logado'])): ?>
                        <li class="nav-item">
                            <a class="nav-link <?php echo ($_SERVER['PHP_SELF'] == '/cadastro.php' ? 'active' : ''); ?>"
                                href="cadastro.php">Cadastro</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?php echo ($_SERVER['PHP_SELF'] == '/consulta.php' ? 'active' : ''); ?>"
                                href="consulta.php">Consulta</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?php echo ($_SERVER['PHP_SELF'] == '/area_usuario.php' ? 'active' : ''); ?>"
                                href="area_usuario.php">
                                Área do Usuário</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-danger" href="/carlos/pw3/logout.php">
                                Sair</a>
                        </li>
                    <?php else: ?>
                        <li class="nav-item">
                            <a class="nav-link" href="login.php">
                                Login</a>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Espaçamento para o conteúdo -->
    <div style="margin-top: 70px;"></div>