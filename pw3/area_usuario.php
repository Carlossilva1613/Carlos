<?php
session_start(); // Adicionar session_start() no início
require_once 'processos/conexao.php';

if (!isset($_SESSION['usuario_logado'])) {
    header('Location: login.php');
    exit();
}

include 'includes/header.php';

// Buscar dados do usuário
try {
    $stmt = $conexao->prepare("SELECT * FROM usuarios WHERE id = ?");
    $stmt->execute([$_SESSION['usuario_logado']]);
    $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$usuario) {
        throw new Exception("Usuário não encontrado");
    }
    ?>

    <div class="container mt-4">
        <?php if (isset($_SESSION['mensagem_sucesso'])): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle"></i> <?php echo $_SESSION['mensagem_sucesso']; ?>
                <button type="button" class="close" data-dismiss="alert">&times;</button>
                <?php unset($_SESSION['mensagem_sucesso']); ?>
            </div>
        <?php endif; ?>

        <div class="row">
            <!-- Perfil do Usuário -->
            <div class="col-md-4 mb-4">
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0"><i class="fas fa-user-circle"></i> Meu Perfil</h5>
                    </div>
                    <div class="card-body">
                        <div class="text-center mb-3">
                            <img src="assets/img/avatar.webp" class="rounded-circle" width="100" alt="Avatar">
                        </div>
                        <h5 class="text-center mb-3"><?php echo htmlspecialchars($usuario['nome'] ?? 'Usuário'); ?></h5>
                        <div class="list-group">
                            <a href="#" class="list-group-item list-group-item-action">
                                <i class="fas fa-edit"></i> Editar Perfil
                            </a>
                            <a href="#" class="list-group-item list-group-item-action">
                                <i class="fas fa-key"></i> Alterar Senha
                            </a>
                            <a href="logout.php" class="list-group-item list-group-item-action text-danger">
                                <i class="fas fa-sign-out-alt"></i> Sair
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Painel Principal -->
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0"><i class="fas fa-tachometer-alt"></i> Painel de Serviços</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <a href="cadastro.php" class="card h-100 text-decoration-none">
                                    <div class="card-body text-center">
                                        <i class="fas fa-plus-circle fa-3x text-primary mb-3"></i>
                                        <h5 class="card-title">Novo Cadastro</h5>
                                        <p class="card-text">Cadastrar novo Produto</p>
                                    </div>
                                </a>
                            </div>
                            <div class="col-md-6 mb-3">
                                <a href="consulta.php" class="card h-100 text-decoration-none">
                                    <div class="card-body text-center">
                                        <i class="fas fa-search fa-3x text-primary mb-3"></i>
                                        <h5 class="card-title">Consultar</h5>
                                        <p class="card-text">Ver todos os Produtos</p>
                                    </div>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Últimos Cadastros -->
                <div class="card mt-4">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0"><i class="fas fa-clock"></i> Últimos Cadastros</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <!-- Aqui você pode adicionar uma tabela com os últimos cadastros -->
                            <table class="table table-hover">
                                <!-- Conteúdo da tabela será adicionado dinamicamente -->
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php
} catch (Exception $e) {
    echo '<div class="alert alert-danger">Erro: ' . htmlspecialchars($e->getMessage()) . '</div>';
}

include 'includes/footer2.php';
?>