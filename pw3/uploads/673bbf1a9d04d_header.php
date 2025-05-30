<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema Imobiliário</title>
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
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
        <div class="container">
            <a class="navbar-brand" href="index.php">
                <img src="assets/img/logo.PNG" height="40">
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
                    <li class="nav-item">
                        <a class="nav-link <?php echo ($_SERVER['PHP_SELF'] == '/consulta.php' ? 'active' : ''); ?>"
                            href="consulta.php">Consulta</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?php echo ($_SERVER['PHP_SELF'] == '/cadastro.php' ? 'active' : ''); ?>"
                            href="cadastro.php">Cadastro</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?php echo ($_SERVER['PHP_SELF'] == '/atualizacao.php' ? 'active' : ''); ?>"
                            href="atualizacao.php">Atualização</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?php echo ($_SERVER['PHP_SELF'] == '/exclusao.php' ? 'active' : ''); ?>"
                            href="exclusao.php">Exclusão</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Espaçamento para o conteúdo -->
    <div style="margin-top: 70px;"></div>