<?php
session_start();
include 'includes/header.php';
require_once 'processos/conexao.php';

// Verificação de login logo no início
$usuarioLogado = isset($_SESSION['usuario_logado']) && $_SESSION['usuario_logado'] === true;

try {
    // Buscar veículos com seus caminhos de imagem concatenados
    $sql = "SELECT v.*, GROUP_CONCAT(img.caminho) as caminhos_imagens 
            FROM tb_veiculo v 
            LEFT JOIN tb_imagem_veiculo img ON v.id_veiculo = img.id_veiculo
            GROUP BY v.id_veiculo 
            ORDER BY v.id_veiculo DESC, v.preco"; // Ordenar por ID decrescente

    $stmt = $conexao->query($sql);
    $veiculos = $stmt->fetchAll(PDO::FETCH_ASSOC); // Renomeado para $veiculos para clareza
    ?>

    <div class="container py-5">
        <div class="text-center mb-5">
            <h1 class="display-4 text-primary">
                DriveX Motors
            </h1>
            <p class="lead text-muted">Seu próximo carro está aqui</p>

            <!-- Adicionar botões de ação condicionais -->
            <?php if ($usuarioLogado): ?>
                <a href="cadastro.php" class="btn btn-success"><i class="fas fa-plus-circle"></i> Cadastrar Novo Veículo</a>
                <a href="consulta.php" class="btn btn-info"><i class="fas fa-search"></i> Consultar Veículos</a>
            <?php else: ?>
                <!-- <a href="login.php" class="btn btn-primary"><i class="fas fa-sign-in-alt"></i> Login para Gerenciar</a> -->
            <?php endif; ?>
        </div>

        <!-- Filtros (Removidos/Simplificados pois tipo_contrato não existe em tb_veiculo) -->
        <!-- <div class="row mb-4">
            <div class="col-12">
                <div class="d-flex flex-wrap justify-content-center gap-2">
                    <button type="button" class="btn btn-outline-primary active m-1" data-filter="todos">Todos</button>
                    // Botões de filtro por tipo_contrato removidos
                </div>
            </div>
        </div> -->

        <!-- Carrossel Principal -->
        <div class="row mb-4">
            <div class="col-12">
                <div id="carrosselPrincipal" class="carousel slide" data-ride="carousel" data-interval="4000">
                    <ol class="carousel-indicators">
                        <li data-target="#carrosselPrincipal" data-slide-to="0" class="active"></li>
                        <li data-target="#carrosselPrincipal" data-slide-to="1"></li>
                        <li data-target="#carrosselPrincipal" data-slide-to="2"></li>
                    </ol>
                    <div class="carousel-inner">
                        <div class="carousel-item active">
                            <img src="assets/img/car1.jpg" class="d-block" alt="Banner 1">
                            <div class="carousel-caption d-none d-md-block">

                            </div>
                        </div>
                        <div class="carousel-item">
                            <img src="assets/img/car2.jpg" class="d-block" alt="Banner 2">
                            <div class="carousel-caption d-none d-md-block">

                            </div>
                        </div>
                        <div class="carousel-item">
                            <img src="assets/img/car3.jpeg" class="d-block" alt="Banner 3">
                            <div class="carousel-caption d-none d-md-block">

                            </div>
                        </div>
                    </div>
                    <a class="carousel-control-prev" href="#carrosselPrincipal" role="button" data-slide="prev">
                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                        <span class="sr-only">Anterior</span>
                    </a>
                    <a class="carousel-control-next" href="#carrosselPrincipal" role="button" data-slide="next">
                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                        <span class="sr-only">Próximo</span>
                    </a>
                </div>
            </div>
        </div>

        <!-- Cards de Veículos -->
        <div class="row">
            <?php foreach ($veiculos as $veiculo):
                $imagens_array = !empty($veiculo['caminhos_imagens']) ? explode(',', $veiculo['caminhos_imagens']) : [];
                ?>
                <div class="col-md-4 mb-4 veiculo-card">
                    <div class="card h-100 shadow-sm hover-card">
                        <!-- Carrossel de Imagens -->
                        <div class="position-relative">
                            <?php if (empty($imagens_array)): ?>
                                <div class="card-img-wrapper fixed-height-img-wrapper">
                                    <img src="assets/img/placeholder_car.png" class="card-img-top default-img card-vehicle-img"
                                        alt="Veículo sem foto">
                                </div>
                            <?php else: ?>
                                <div id="carrossel-<?php echo $veiculo['id_veiculo']; ?>" class="carousel slide"
                                    data-ride="carousel">
                                    <div class="carousel-inner">
                                        <?php foreach ($imagens_array as $index => $caminho_imagem): ?>
                                            <div class="carousel-item <?php echo $index === 0 ? 'active' : ''; ?>">
                                                <div class="card-img-wrapper fixed-height-img-wrapper">
                                                    <img src="uploads/<?php echo trim($caminho_imagem); ?>"
                                                        class="card-img-top card-vehicle-img"
                                                        alt="<?php echo htmlspecialchars($veiculo['marca'] . ' ' . $veiculo['modelo']); ?>">
                                                </div>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                    <?php if (count($imagens_array) > 1): ?>
                                        <a class="carousel-control-prev" href="#carrossel-<?php echo $veiculo['id_veiculo']; ?>"
                                            role="button" data-slide="prev">
                                            <span class="carousel-control-prev-icon"></span>
                                        </a>
                                        <a class="carousel-control-next" href="#carrossel-<?php echo $veiculo['id_veiculo']; ?>"
                                            role="button" data-slide="next">
                                            <span class="carousel-control-next-icon"></span>
                                        </a>
                                    <?php endif; ?>
                                </div>
                            <?php endif; ?>

                        </div>

                        <!-- Conteúdo do card -->
                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title"><?php echo htmlspecialchars($veiculo['marca'] . ' ' . $veiculo['modelo']); ?>
                            </h5>
                            <p class="card-text">
                                <i class="fas fa-calendar-alt text-primary"></i> Ano:
                                <?php echo htmlspecialchars($veiculo['ano']); ?>
                            </p>
                            <p class="card-text">
                                <i class="fas fa-id-card text-primary"></i> Placa:
                                <?php echo htmlspecialchars($veiculo['placa']); ?>
                            </p>
                            <p class="card-text">
                                <i class="fas fa-palette text-primary"></i> Cor:
                                <?php echo htmlspecialchars($veiculo['cor']); ?>
                            </p>
                            <h4 class="text-primary mt-auto mb-3">
                                R$ <?php echo number_format(floatval($veiculo['preco']), 2, ',', '.'); ?>
                            </h4>
                            <a href="detalhes_veiculo.php?id_veiculo=<?php echo $veiculo['id_veiculo']; ?>"
                                class="btn btn-outline-primary btn-block">
                                <i class="fas fa-info-circle"></i> Mais Detalhes
                            </a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <script>
        $(document).ready(function () {
            // Lógica de filtro removida/simplificada
            // $('.btn-group .btn').click(function () {
            // $('.btn-group .btn').removeClass('active');
            // $(this).addClass('active');
            // const filtro = $(this).data('filter');
            // if (filtro === 'todos') {
            // $('.veiculo-card').show();
            // } else {
            // $('.veiculo-card').hide();
            // $(`.veiculo-card[data-algum-atributo-existente="${filtro}"]`).show(); // Ajustar se for implementar novo filtro
            // }
            // });
        });
    </script>

    <?php
} catch (PDOException $e) {
    echo '<div class="alert alert-danger">Erro ao carregar veículos: ' . $e->getMessage() . '</div>';
}

include 'includes/footer.php';
?>

<head>
    <link rel="stylesheet" href="assets/css/main.css">
</head>

<style>
    /* Adicione este CSS para padronizar a altura das imagens dos cards */
    .fixed-height-img-wrapper {
        height: 200px;
        /* Defina a altura desejada para a área da imagem */
        overflow: hidden;
        /* Garante que nada ultrapasse o wrapper */
        display: flex;
        /* Para centralizar a imagem se ela for menor que o wrapper com object-fit: contain */
        align-items: center;
        /* Centraliza verticalmente */
        justify-content: center;
        /* Centraliza horizontalmente */
        background-color: #f8f9fa;
        /* Cor de fundo caso a imagem não preencha tudo com 'contain' */
    }

    .card-vehicle-img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        /* Faz a imagem cobrir a área, cortando se necessário. Use 'contain' para ver a imagem inteira. */
    }

    .default-img {
        /* Estilo específico para a imagem placeholder, se necessário */
        object-fit: contain;
        /* Para o placeholder, 'contain' pode ser melhor */
    }

    /* Ajustes para o carrossel dentro do card */
    .card .carousel-item .card-img-wrapper {
        border-top-left-radius: calc(.25rem - 1px);
        border-top-right-radius: calc(.25rem - 1px);
    }
</style>