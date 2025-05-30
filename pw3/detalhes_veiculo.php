<?php
session_start();
include 'includes/header.php';
require_once 'processos/conexao.php';

$veiculo = null;
$imagens_veiculo = [];

if (isset($_GET['id_veiculo']) && is_numeric($_GET['id_veiculo'])) {
    $id_veiculo = intval($_GET['id_veiculo']);

    try {
        // Buscar dados do veículo e nome do vendedor
        $sql_veiculo = "SELECT v.*, u.nome as nome_vendedor 
                        FROM tb_veiculo v
                        JOIN tb_usuario u ON v.id_usuario = u.id_usuario
                        WHERE v.id_veiculo = :id_veiculo";
        $stmt_veiculo = $conexao->prepare($sql_veiculo);
        $stmt_veiculo->bindParam(':id_veiculo', $id_veiculo, PDO::PARAM_INT);
        $stmt_veiculo->execute();
        $veiculo = $stmt_veiculo->fetch(PDO::FETCH_ASSOC);

        if ($veiculo) {
            // Buscar imagens do veículo
            $sql_imagens = "SELECT caminho FROM tb_imagem_veiculo WHERE id_veiculo = :id_veiculo ORDER BY id_imagem";
            $stmt_imagens = $conexao->prepare($sql_imagens);
            $stmt_imagens->bindParam(':id_veiculo', $id_veiculo, PDO::PARAM_INT);
            $stmt_imagens->execute();
            $imagens_veiculo = $stmt_imagens->fetchAll(PDO::FETCH_ASSOC);
        }
    } catch (PDOException $e) {
        // Tratar erro de banco de dados, talvez registrar e mostrar uma mensagem amigável
        $veiculo = null; // Garante que a página não tente renderizar dados parciais
        echo '<div class="container mt-4"><div class="alert alert-danger">Erro ao carregar dados do veículo: ' . htmlspecialchars($e->getMessage()) . '</div></div>';
    }
}

?>

<div class="container py-5">
    <?php if ($veiculo): ?>
        <div class="row">
            <div class="col-lg-8 offset-lg-2">
                <div class="card shadow-lg">
                    <div class="card-header bg-primary text-white">
                        <h2 class="mb-0">
                            <i class="fas fa-car"></i>
                            <?php echo htmlspecialchars($veiculo['marca'] . ' ' . $veiculo['modelo']); ?>
                        </h2>
                    </div>
                    <div class="card-body">
                        <!-- Carrossel de Imagens do Veículo -->
                        <?php if (!empty($imagens_veiculo)): ?>
                            <div id="carrosselDetalhesVeiculo" class="carousel slide mb-4" data-ride="carousel">
                                <ol class="carousel-indicators">
                                    <?php foreach ($imagens_veiculo as $index => $img): ?>
                                        <li data-target="#carrosselDetalhesVeiculo" data-slide-to="<?php echo $index; ?>"
                                            class="<?php echo $index === 0 ? 'active' : ''; ?>"></li>
                                    <?php endforeach; ?>
                                </ol>
                                <div class="carousel-inner rounded">
                                    <?php foreach ($imagens_veiculo as $index => $img): ?>
                                        <div class="carousel-item <?php echo $index === 0 ? 'active' : ''; ?>">
                                            <div class="d-flex align-items-center justify-content-center bg-light"
                                                style="height: 400px;">
                                                <img src="uploads/<?php echo htmlspecialchars(trim($img['caminho'])); ?>"
                                                    class="d-block" style="max-height: 100%; max-width:100%; width: auto;"
                                                    alt="Imagem <?php echo $index + 1; ?> de <?php echo htmlspecialchars($veiculo['marca'] . ' ' . $veiculo['modelo']); ?>">
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                                <?php if (count($imagens_veiculo) > 1): ?>
                                    <a class="carousel-control-prev" href="#carrosselDetalhesVeiculo" role="button"
                                        data-slide="prev">
                                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                        <span class="sr-only">Anterior</span>
                                    </a>
                                    <a class="carousel-control-next" href="#carrosselDetalhesVeiculo" role="button"
                                        data-slide="next">
                                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                        <span class="sr-only">Próximo</span>
                                    </a>
                                <?php endif; ?>
                            </div>
                        <?php else: ?>
                            <div class="text-center mb-4">
                                <img src="assets/img/placeholder_car.png" class="img-fluid rounded" style="max-height: 400px;"
                                    alt="Veículo sem foto">
                                <p class="text-muted mt-2">Nenhuma imagem disponível para este veículo.</p>
                            </div>
                        <?php endif; ?>

                        <h3 class="text-success mb-3">R$
                            <?php echo number_format(floatval($veiculo['preco']), 2, ',', '.'); ?>
                        </h3>

                        <ul class="list-group list-group-flush">
                            <li class="list-group-item"><strong><i class="fas fa-calendar-alt text-info"></i> Ano:</strong>
                                <?php echo htmlspecialchars($veiculo['ano']); ?></li>
                            <li class="list-group-item"><strong><i class="fas fa-id-card text-info"></i> Placa:</strong>
                                <?php echo htmlspecialchars($veiculo['placa']); ?></li>
                            <li class="list-group-item"><strong><i class="fas fa-palette text-info"></i> Cor:</strong>
                                <?php echo htmlspecialchars($veiculo['cor']); ?></li>
                            <?php if (!empty($veiculo['titulo'])): ?>
                                <li class="list-group-item"><strong><i class="fas fa-heading text-info"></i> Título do
                                        Anúncio:</strong> <?php echo htmlspecialchars($veiculo['titulo']); ?></li>
                            <?php endif; ?>
                            <?php if (!empty($veiculo['descricao'])): ?>
                                <li class="list-group-item">
                                    <strong><i class="fas fa-align-left text-info"></i> Descrição:</strong>
                                    <p class="mt-2" style="white-space: pre-wrap;">
                                        <?php echo htmlspecialchars($veiculo['descricao']); ?>
                                    </p>
                                </li>
                            <?php endif; ?>
                            <li class="list-group-item"><strong><i class="fas fa-user text-info"></i>
                                    Vendedor:</strong> <?php echo htmlspecialchars($veiculo['nome_vendedor']); ?></li>
                            <li class="list-group-item"><strong><i class="fas fa-clock text-info"></i> Cadastrado
                                    em:</strong>
                                <?php echo htmlspecialchars(date('d/m/Y H:i', strtotime($veiculo['criado_em']))); ?></li>
                        </ul>
                    </div>
                    <div class="card-footer text-center">
                        <a href="index.php" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Voltar para a Página
                            Inicial</a>
                        <!-- Poderia adicionar um botão de "Tenho Interesse" ou contato aqui -->
                    </div>
                </div>
            </div>
        </div>
    <?php else: ?>
        <div class="alert alert-warning text-center" role="alert">
            <h4 class="alert-heading"><i class="fas fa-exclamation-triangle"></i> Veículo não encontrado!</h4>
            <p>O veículo que você está tentando visualizar não existe ou o ID é inválido.</p>
            <hr>
            <a href="index.php" class="btn btn-primary"><i class="fas fa-home"></i> Voltar para a Página Inicial</a>
        </div>
    <?php endif; ?>
</div>

<style>
    .card-header h2 {
        font-size: 1.75rem;
    }

    .list-group-item strong {
        margin-right: 8px;
    }

    .carousel-item img {
        object-fit: contain;
        /* Para garantir que a imagem inteira seja visível sem cortar */
    }
</style>

<?php include 'includes/footer.php'; ?>