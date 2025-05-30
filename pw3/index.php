<?php
session_start();
include 'includes/header.php';
require_once 'processos/conexao.php';

// Verificação de login logo no início
$usuarioLogado = isset($_SESSION['usuario_logado']) && $_SESSION['usuario_logado'] === true;

try {
    // Buscar imóveis com todas suas imagens
    $sql = "SELECT i.*, GROUP_CONCAT(img.id_imagem) as imagens 
            FROM tb_veiculo i 
            LEFT JOIN tb_imagem_veiculo img ON i.id_veiculo = img.id_veiculo
            GROUP BY i.id_veiculo 
            ORDER BY i.id_veiculo, i.preco";

    $stmt = $conexao->query($sql);
    $imoveis = $stmt->fetchAll(PDO::FETCH_ASSOC);
    ?>

    <div class="container py-5">
        <div class="text-center mb-5">
            <h1 class="display-4 text-primary">
                DriveX Motors
            </h1>
            <p class="lead text-muted">Seu próximo carro está aqui</p>

            <!-- Adicionar botões de ação condicionais -->

        </div>

        <!-- Filtros -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="d-flex flex-wrap justify-content-center gap-2">
                    <button type="button" class="btn btn-outline-primary active m-1" data-filter="todos">Todos</button>
                    <button type="button" class="btn btn-outline-primary m-1" data-filter="C">Compra</button>
                    <button type="button" class="btn btn-outline-primary m-1" data-filter="V">Venda</button>
                    <button type="button" class="btn btn-outline-primary m-1" data-filter="L">Locadora</button>
                </div>
            </div>
        </div>

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

        <!-- Cards de Imóveis -->
        <div class="row">
            <?php foreach ($imoveis as $imovel):
                $imagens = $imovel['imagens'] ? explode(',', $imovel['imagens']) : [];
                ?>
                <div class="col-md-4 mb-4 imovel-card" data-tipo="<?php echo $imovel['tipo_contrato']; ?>">
                    <div class="card h-100 shadow-sm hover-card">
                        <!-- Carrossel de Imagens -->
                        <div class="position-relative">
                            <?php if (empty($imagens)): ?>
                                <div class="card-img-wrapper">
                                    <img src="assets/img/prisma.webp" class="card-img-top default-img" alt="Imóvel sem foto">
                                </div>
                            <?php else: ?>
                                <div id="carrossel-<?php echo $imovel['id']; ?>" class="carousel slide" data-ride="carousel">
                                    <div class="carousel-inner">
                                        <?php foreach ($imagens as $index => $imagem): ?>
                                            <div class="carousel-item <?php echo $index === 0 ? 'active' : ''; ?>">
                                                <div class="card-img-wrapper">
                                                    <img src="uploads/<?php echo trim($imagem); ?>" class="card-img-top" alt="Imóvel">
                                                </div>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                    <?php if (count($imagens) > 1): ?>
                                        <a class="carousel-control-prev" href="#carrossel-<?php echo $imovel['id']; ?>" role="button"
                                            data-slide="prev">
                                            <span class="carousel-control-prev-icon"></span>
                                        </a>
                                        <a class="carousel-control-next" href="#carrossel-<?php echo $imovel['id']; ?>" role="button"
                                            data-slide="next">
                                            <span class="carousel-control-next-icon"></span>
                                        </a>
                                    <?php endif; ?>
                                </div>
                            <?php endif; ?>

                            <div class="position-absolute" style="top: 10px; right: 10px;">
                                <span
                                    class="badge badge-<?php echo $imovel['tipo_contrato'] == 'C' ? 'success' : 'info'; ?> p-2">
                                    <?php echo $imovel['tipo_contrato'] == 'C' ? 'Venda' : 'Aluguel'; ?>
                                </span>
                            </div>
                        </div>

                        <!-- Conteúdo do card -->
                        <div class="card-body">
                            <h5 class="card-title"><?php echo htmlspecialchars($imovel['tipo']); ?></h5>
                            <p class="card-text">
                                <i class="fas fa-map-marker-alt text-primary"></i>
                                <?php echo htmlspecialchars($imovel['endereco']); ?>
                            </p>
                            <p class="card-text">
                                <i class="fas fa-ruler-combined text-primary"></i>
                                <?php echo htmlspecialchars($imovel['lote']); ?> m²
                            </p>
                            <h4 class="text-primary mb-3">
                                R$ <?php echo number_format($imovel['valor'], 2, ',', '.'); ?>
                            </h4>
                            <button class="btn btn-outline-primary btn-block"
                                onclick="mostrarDetalhes(<?php echo htmlspecialchars(json_encode($imovel)); ?>)">
                                <i class="fas fa-info-circle"></i> Mais Detalhes
                            </button>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <script>
        $(document).ready(function () {
            // Filtro de imóveis
            $('.btn-group .btn').click(function () {
                $('.btn-group .btn').removeClass('active');
                $(this).addClass('active');

                const filtro = $(this).data('filter');

                if (filtro === 'todos') {
                    $('.imovel-card').show();
                } else {
                    $('.imovel-card').hide();
                    $(`.imovel-card[data-tipo="${filtro}"]`).show();
                }
            });
        });

        // Função para mostrar detalhes do imóvel
        function mostrarDetalhes(imovel) {
            const headerClass = imovel.tipo_contrato === 'C' ? 'success' : 'info';
            const tipoTexto = imovel.tipo_contrato === 'C' ? 'Venda' : 'Aluguel';

            Swal.fire({
                title: '',
                html: `
                                                                                                                                    <div class="modal-imovel">
                                                                                                                                        <div class="modal-header-custom bg-${headerClass} text-white p-3 rounded-top mb-3">
                                                                                                                                            <div class="d-flex justify-content-between align-items-center">
                                                                                                                                                <h5 class="mb-0"><i class="fas fa-building"></i> ${imovel.tipo}</h5>
                                                                                                                                                <span class="badge badge-light">${tipoTexto}</span>
                                                                                                                                            </div>
                                                                                                                                        </div>
                                                                                                                                        <div id="carrosselDetalhes" class="carousel slide mb-3" data-ride="carousel">
                                                                                                                                            <div class="carousel-inner rounded">
                                                                                                                                                ${imovel.imagens ?
                    imovel.imagens.split(',').map((img, index) => `
                                                                                                                                                        <div class="carousel-item ${index === 0 ? 'active' : ''}">
                                                                                                                                                            <div class="d-flex align-items-center justify-content-center bg-light" style="height: 300px;">
                                                                                                                                                                <img src="uploads/${img.trim()}" class="d-block" style="max-height: 100%; width: auto;" alt="Imóvel">
                                                                                                                                                            </div>
                                                                                                                                                        </div>
                                                                                                                                                    `).join('')
                    :
                    `<div class="carousel-item active">
                                                                                                                                                        <div class="d-flex align-items-center justify-content-center bg-light" style="height: 300px;">
                                                                                                                                                            <img src="img/prisma.webp" class="d-block" style="max-height: 80%; width: auto;" alt="Imóvel">
                                                                                                                                                        </div>
                                                                                                                                                    </div>`
                }
                                                                                                                                            </div>
                                                                                                                                            ${imovel.imagens && imovel.imagens.split(',').length > 1 ? `
                                                                                                                                                <a class="carousel-control-prev" href="#carrosselDetalhes" role="button" data-slide="prev">
                                                                                                                                                    <span class="carousel-control-prev-icon"></span>
                                                                                                                                                </a>
                                                                                                                                                <a class="carousel-control-next" href="#carrosselDetalhes" role="button" data-slide="next">
                                                                                                                                                    <span class="carousel-control-next-icon"></span>
                                                                                                                                                </a>
                                                                                                                                            ` : ''}
                                                                                                                                        </div>
                                                                                                                                        <div class="detalhes-imovel mt-3">
                                                                                                                                            <div class="row g-3">
                                                                                                                                                <div class="col-12">
                                                                                                                                                    <div class="p-2 bg-light rounded">
                                                                                                                                                        <strong><i class="fas fa-map-marker-alt text-danger"></i> Localização:</strong>
                                                                                                                                                        <p class="mb-0 ml-4">${imovel.endereco}</p>
                                                                                                                                                    </div>
                                                                                                                                                </div>
                                                                                                                                                <div class="col-md-6">
                                                                                                                                                    <div class="p-2 bg-light rounded">
                                                                                                                                                        <strong><i class="fas fa-dollar-sign text-success"></i> Valor:</strong>
                                                                                                                                                        <p class="mb-0 ml-4 text-success h5">
                                                                                                                                                            R$ ${parseFloat(imovel.valor).toLocaleString('pt-BR', { minimumFractionDigits: 2 })}
                                                                                                                                                        </p>
                                                                                                                                                    </div>
                                                                                                                                                </div>
                                                                                                                                                <div class="col-md-6">
                                                                                                                                                    <div class="p-2 bg-light rounded">
                                                                                                                                                        <strong><i class="fas fa-ruler-combined text-primary"></i> Área:</strong>
                                                                                                                                                        <p class="mb-0 ml-4">${imovel.lote} m²</p>
                                                                                                                                                    </div>
                                                                                                                                                </div>
                                                                                                                                                <div class="col-12">
                                                                                                                                                    <div class="p-2 border-top mt-2">
                                                                                                                                                        <h6 class="mb-2"><i class="fas fa-address-card text-secondary"></i> Contato:</h6>
                                                                                                                                                        <div class="ml-4">
                                                                                                                                                            <p class="mb-1">
                                                                                                                                                                <i class="fas fa-phone text-secondary"></i> ${imovel.telefone}
                                                                                                                                                            </p>
                                                                                                                                                            <p class="mb-0">
                                                                                                                                                                <i class="fas fa-envelope text-secondary"></i> ${imovel.email}
                                                                                                                                                            </p>
                                                                                                                                                        </div>
                                                                                                                                                    </div>
                                                                                                                                                </div>
                                                                                                                                            </div>
                                                                                                                                        </div>
                                                                                                                                    </div>`,
                showConfirmButton: false,
                showCloseButton: true,
                customClass: {
                    popup: 'swal-imovel-popup'
                }
            });
        }
    </script>

    <?php
} catch (PDOException $e) {
    echo '<div class="alert alert-danger">Erro ao carregar imóveis: ' . $e->getMessage() . '</div>';
}

include 'includes/footer.php';
?>

<head>
    <link rel="stylesheet" href="assets/css/main.css">
</head>