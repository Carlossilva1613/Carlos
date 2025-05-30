<?php
session_start();

if (isset($_GET['erro']) && $_GET['erro'] == 'acesso_negado') {
    echo '<div class="alert alert-danger">Você precisa estar logado para acessar esta página.</div>';
}
include 'includes/header.php';
?>

<!-- Adicionar a inclusão dos arquivos CSS corretos -->
<link rel="stylesheet" href="assets/css/style.css">

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <?php if (isset($_GET['logout']) && $_GET['logout'] == 'success'): ?>
                <div class="alert alert-success">
                    <i class="fas fa-check-circle"></i> Logout realizado com sucesso!
                </div>
            <?php endif; ?>

            <?php if (isset($_GET['erro']) && $_GET['erro'] == 'login'): ?>
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-circle"></i> Email ou senha incorretos.
                </div>
            <?php endif; ?>
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h3 class="mb-0">Login</h3>
                </div>
                <div class="card-body">
                    <form action="processos/processa_login.php" method="POST">
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="email" name="email" id="email" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="password">Senha</label>
                            <div class="input-group">
                                <input type="password" name="senha" id="password" class="form-control" required>
                                <div class="input-group-append">
                                    <button type="button" class="btn btn-outline-secondary" id="togglePassword">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary btn-block">Entrar</button>
                    </form>
                    <div class="mt-3 text-center">
                        <a href="cadastro_usuario.php">Não tem uma conta? Crie uma agora</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.getElementById('togglePassword').addEventListener('click', function () {
        const passwordField = document.getElementById('password');
        const passwordFieldType = passwordField.getAttribute('type');
        if (passwordFieldType === 'password') {
            passwordField.setAttribute('type', 'text');
            this.innerHTML = '<i class="fas fa-eye-slash"></i>';
        } else {
            passwordField.setAttribute('type', 'password');
            this.innerHTML = '<i class="fas fa-eye"></i>';
        }
    });
</script>

<!-- Adicionar a inclusão dos arquivos JS corretos -->
<script src="assets/js/validacao.js"></script>

<?php
include 'includes/footer2.php';
?>