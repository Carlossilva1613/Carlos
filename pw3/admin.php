<?php
session_start();
if (!isset($_SESSION['usuario_logado'])) {
    header('Location: login.php');
    exit();
}
include 'includes/header.php';
?>

<div class="container mt-5">
    <?php if (isset($_SESSION['mensagem_sucesso'])): ?>
        <div class="alert alert-success">
            <?php echo $_SESSION['mensagem_sucesso']; ?>
            <?php unset($_SESSION['mensagem_sucesso']); ?>
        </div>
    <?php endif; ?>
    <h1>Bem-vindo à página de administração</h1>
    <p>Você está logado como usuário ID: <?php echo $_SESSION['usuario_logado']; ?></p>
    <a href="logout.php" class="btn btn-danger">Logout</a>
</div>

<?php
include 'includes/footer2.php';
?>