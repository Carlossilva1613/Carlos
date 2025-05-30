<?php
session_start();
include 'includes/header.php';

// Adicionar este bloco para exibir mensagens
if (isset($_SESSION['sucesso'])) {
    echo '<div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle"></i> ' . htmlspecialchars($_SESSION['sucesso']) . '
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
          </div>';
    unset($_SESSION['sucesso']);
}

if (isset($_SESSION['erro'])) {
    echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-triangle"></i> ' . htmlspecialchars($_SESSION['erro']) . '
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
          </div>';
    unset($_SESSION['erro']);
}
// Fim do bloco de mensagens
?>

<div class="container mt-4">
    <div class="row mb-4">
        <div class="col-12">
            <h1 class="text-primary">
                <i class="fas fa-search"></i> Consulta de Veículos
            </h1>
        </div>
    </div>

    <!-- Filtros -->
    <div class="card mb-4 shadow-sm">
        <div class="card-body bg-light">
            <form method="GET" class="mb-0">
                <div class="row align-items-end">
                    <div class="col-md-4">
                        <div class="form-group mb-md-0">
                            <label for="busca_texto"><i class="fas fa-font"></i> Marca/Modelo:</label>
                            <input type="text" class="form-control" id="busca_texto" name="busca_texto"
                                placeholder="Digite marca ou modelo"
                                value="<?php echo isset($_GET['busca_texto']) ? htmlspecialchars($_GET['busca_texto']) : ''; ?>">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group mb-md-0">
                            <label for="busca_placa"><i class="fas fa-id-card"></i> Placa:</label>
                            <input type="text" class="form-control" id="busca_placa" name="busca_placa"
                                placeholder="Digite a placa"
                                value="<?php echo isset($_GET['busca_placa']) ? htmlspecialchars($_GET['busca_placa']) : ''; ?>">
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group mb-md-0">
                            <button type="submit" class="btn btn-primary btn-block">
                                <i class="fas fa-search"></i> Buscar
                            </button>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group mb-md-0">
                            <a href="consulta.php" class="btn btn-secondary btn-block btn-limpar-filtros">
                                <i class="fas fa-eraser"></i> Limpar
                            </a>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <?php
    require_once 'processos/conexao.php';

    try {
        $where = [];
        $params = [];

        // Filtro por marca ou modelo
        if (isset($_GET['busca_texto']) && !empty($_GET['busca_texto'])) {
            $where[] = "(v.marca LIKE :busca_texto OR v.modelo LIKE :busca_texto)"; // Adicionar alias v.
            $params[':busca_texto'] = '%' . $_GET['busca_texto'] . '%';
        }

        // Filtro por placa
        if (isset($_GET['busca_placa']) && !empty($_GET['busca_placa'])) {
            $where[] = "v.placa LIKE :busca_placa"; // Adicionar alias v.
            $params[':busca_placa'] = '%' . $_GET['busca_placa'] . '%';
        }

        $whereClause = !empty($where) ? " WHERE " . implode(" AND ", $where) : "";

        // Modificar SQL para incluir caminhos das imagens e nome do usuário
        $sql = "SELECT v.id_veiculo, v.marca, v.modelo, v.ano, v.placa, v.cor, v.preco, v.titulo, v.descricao, v.criado_em, v.id_usuario,
                       u.nome as nome_usuario,
                       GROUP_CONCAT(iv.caminho SEPARATOR ',') as caminhos_imagens
                FROM tb_veiculo v
                LEFT JOIN tb_imagem_veiculo iv ON v.id_veiculo = iv.id_veiculo
                LEFT JOIN tb_usuario u ON v.id_usuario = u.id_usuario "
            . $whereClause . " 
                GROUP BY v.id_veiculo
                ORDER BY v.id_veiculo DESC";

        $stmt = $conexao->prepare($sql);
        $stmt->execute($params);
        $registros = $stmt->fetchAll();
        ?>

        <!-- Contador de Registros -->
        <div class="alert alert-info">
            <i class="fas fa-info-circle"></i>
            Total de registros encontrados: <strong><?php echo count($registros); ?></strong>
        </div>

        <?php
        if (count($registros) > 0) {
            ?>
            <div class="table-responsive">
                <table class="table table-hover table-striped table-bordered">
                    <thead class="thead-dark">
                        <tr>
                            <th><i class="fas fa-hashtag"></i> ID</th>
                            <th><i class="fas fa-car"></i> Marca</th>
                            <th><i class="fas fa-car-side"></i> Modelo</th>
                            <th><i class="fas fa-calendar-alt"></i> Ano</th>
                            <th><i class="fas fa-id-card"></i> Placa</th>
                            <th><i class="fas fa-palette"></i> Cor</th>
                            <th><i class="fas fa-dollar-sign"></i> Preço</th>
                            <th><i class="fas fa-cogs"></i> Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($registros as $registro): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($registro['id_veiculo']); ?></td>
                                <td><?php echo htmlspecialchars($registro['marca']); ?></td>
                                <td><?php echo htmlspecialchars($registro['modelo']); ?></td>
                                <td><?php echo htmlspecialchars($registro['ano']); ?></td>
                                <td><?php echo htmlspecialchars($registro['placa']); ?></td>
                                <td><?php echo htmlspecialchars($registro['cor']); ?></td>
                                <td>R$ <?php echo number_format(floatval($registro['preco']), 2, ',', '.'); ?></td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="atualizacao.php?id_veiculo=<?php echo $registro['id_veiculo']; ?>"
                                            class="btn btn-warning btn-sm" title="Editar">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <a href="exclusao.php?id_veiculo=<?php echo $registro['id_veiculo']; ?>"
                                            class="btn btn-danger btn-sm" title="Excluir"
                                            onclick="return confirm('Tem certeza que deseja excluir este veículo?');">
                                            <i class="fas fa-trash"></i>
                                        </a>
                                        <button type="button" class="btn btn-info btn-sm" title="Ver Detalhes" data-toggle="modal"
                                            data-target="#detailsModal"
                                            data-idveiculo="<?php echo htmlspecialchars($registro['id_veiculo']); ?>"
                                            data-marca="<?php echo htmlspecialchars($registro['marca']); ?>"
                                            data-modelo="<?php echo htmlspecialchars($registro['modelo']); ?>"
                                            data-ano="<?php echo htmlspecialchars($registro['ano']); ?>"
                                            data-placa="<?php echo htmlspecialchars($registro['placa']); ?>"
                                            data-cor="<?php echo htmlspecialchars($registro['cor']); ?>"
                                            data-preco="R$ <?php echo number_format(floatval($registro['preco']), 2, ',', '.'); ?>"
                                            data-titulo="<?php echo htmlspecialchars($registro['titulo'] ?? ''); ?>"
                                            data-descricao="<?php echo htmlspecialchars($registro['descricao'] ?? ''); ?>"
                                            data-criadoem="<?php echo htmlspecialchars(date('d/m/Y H:i', strtotime($registro['criado_em']))); ?>"
                                            data-idusuario="<?php echo htmlspecialchars($registro['id_usuario']); ?>"
                                            data-nomeusuario="<?php echo htmlspecialchars($registro['nome_usuario'] ?? 'Não informado'); ?>"
                                            data-imagens="<?php echo htmlspecialchars($registro['caminhos_imagens'] ?? ''); ?>">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <!-- Botões de Exportação e Voltar -->
            <div class="row mt-3">
                <div class="col-md-6 mb-2 mb-md-0">
                    <?php /* Botões de exportação removidos */ ?>
                </div>
                <div class="col-md-6 text-md-right">
                    <a href="index.php" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Voltar
                    </a>
                </div>
            </div>
            <?php
        } else {
            ?>
            <div class="alert alert-warning text-center">
                <i class="fas fa-exclamation-triangle"></i>
                Nenhum registro encontrado com os filtros selecionados.
            </div>
            <div class="text-center">
                <a href="index.php" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Voltar
                </a>
            </div>
            <?php
        }
    } catch (PDOException $e) {
        echo '<div class="alert alert-danger">
                <i class="fas fa-exclamation-circle"></i> 
                Erro ao buscar registros: ' . htmlspecialchars($e->getMessage()) . '
              </div>';
    }
    ?>
</div>

<!-- Modal de Detalhes -->
<div class="modal fade" id="detailsModal" tabindex="-1" role="dialog" aria-labelledby="detailsModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-info text-white">
                <h5 class="modal-title" id="detailsModalLabel"><i class="fas fa-car-alt"></i> Detalhes do Veículo</h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <p><strong>ID do Veículo:</strong> <span id="modal_id_veiculo"></span></p>
                        <p><strong>Marca:</strong> <span id="modal_marca"></span></p>
                        <p><strong>Modelo:</strong> <span id="modal_modelo"></span></p>
                        <p><strong>Ano:</strong> <span id="modal_ano"></span></p>
                        <p><strong>Placa:</strong> <span id="modal_placa"></span></p>
                        <p><strong>Cor:</strong> <span id="modal_cor"></span></p>
                    </div>
                    <div class="col-md-6">
                        <p><strong>Preço:</strong> <span id="modal_preco"></span></p>
                        <p><strong>Título do Anúncio:</strong> <span id="modal_titulo"></span></p>
                        <p><strong>Descrição:</strong> <span id="modal_descricao" style="white-space: pre-wrap;"></span>
                        </p>
                        <p><strong>Data de Cadastro:</strong> <span id="modal_criadoem"></span></p>
                        <p><strong>Vendedor:</strong> <span id="modal_nome_usuario"></span></p>
                        <?php /* Removido o campo ID do Usuário daqui, pois agora exibimos o nome. 
      Se ainda precisar do ID para alguma lógica JS, ele já está sendo pego e pode ser usado internamente.
      Se quiser exibir AMBOS, ID e Nome, adicione um novo <p> para o ID.
      Exemplo: <p><strong>ID do Vendedor:</strong> <span id="modal_id_usuario_display"></span></p>
      E no JS: modal.find('#modal_id_usuario_display').text(button.data('idusuario'));
*/ ?>
                    </div>
                </div>
                <!-- Div para exibir as imagens -->
                <hr>
                <h5><i class="fas fa-images"></i> Imagens</h5>
                <div id="modal_imagens_container" class="row mt-2">
                    <!-- As imagens serão inseridas aqui pelo JavaScript -->
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
            </div>
        </div>
    </div>
</div>


<!-- CSS Adicional -->
<style>
    .table th {
        background-color: #343a40;
        color: white;
    }

    .table-hover tbody tr:hover {
        background-color: rgba(0, 123, 255, .075);
    }

    .badge {
        font-size: 90%;
    }

    .btn-group .btn {
        padding: .25rem .5rem;
    }

    .card {
        border-radius: .5rem;
    }

    .alert {
        border-radius: .5rem;
    }

    .modal-body p {
        margin-bottom: 0.5rem;
    }
</style>

<!-- JavaScript para Exportação e Modal -->
<script>
    $(document).ready(function () {
        // Persistir valores dos filtros após submit
        const urlParams = new URLSearchParams(window.location.search);

        if (urlParams.has('busca_texto')) {
            $('#busca_texto').val(urlParams.get('busca_texto'));
        }
        if (urlParams.has('busca_placa')) {
            $('#busca_placa').val(urlParams.get('busca_placa'));
        }

        // Limpar filtros - o botão já é um link para consulta.php
        // $('.btn-limpar-filtros').click(function (e) {
        // e.preventDefault();
        // window.location.href = 'consulta.php';
        // });

        // Modal de Detalhes
        $('#detailsModal').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget); // Botão que acionou o modal
            var modal = $(this);

            modal.find('#modal_id_veiculo').text(button.data('idveiculo'));
            modal.find('#modal_marca').text(button.data('marca'));
            modal.find('#modal_modelo').text(button.data('modelo'));
            modal.find('#modal_ano').text(button.data('ano'));
            modal.find('#modal_placa').text(button.data('placa'));
            modal.find('#modal_cor').text(button.data('cor'));
            modal.find('#modal_preco').text(button.data('preco'));
            modal.find('#modal_titulo').text(button.data('titulo'));
            modal.find('#modal_descricao').text(button.data('descricao'));
            modal.find('#modal_criadoem').text(button.data('criadoem'));
            // modal.find('#modal_id_usuario').text(button.data('idusuario')); // Esta linha pode ser removida se o ID não for exibido diretamente.
            modal.find('#modal_nome_usuario').text(button.data('nomeusuario'));


            // Limpar container de imagens e adicionar novas
            var imagensContainer = modal.find('#modal_imagens_container');
            imagensContainer.empty(); // Limpa imagens anteriores
            var imagensString = button.data('imagens');

            if (imagensString) {
                var imagensArray = imagensString.split(',');
                if (imagensArray.length > 0 && imagensArray[0].trim() !== "") {
                    // Cria um carrossel simples para as imagens no modal
                    let carouselId = "carouselModal" + button.data('idveiculo');
                    let carouselHtml = `<div id="${carouselId}" class="carousel slide col-12" data-ride="carousel"><div class="carousel-inner">`;

                    imagensArray.forEach(function (caminho, index) {
                        carouselHtml += `
                            <div class="carousel-item ${index === 0 ? 'active' : ''}">
                                <img src="uploads/${caminho.trim()}" class="d-block w-100" alt="Imagem do veículo ${index + 1}" style="max-height: 300px; object-fit: contain;">
                            </div>`;
                    });
                    carouselHtml += `</div>`;

                    if (imagensArray.length > 1) {
                        carouselHtml += `
                            <a class="carousel-control-prev" href="#${carouselId}" role="button" data-slide="prev">
                                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                <span class="sr-only">Anterior</span>
                            </a>
                            <a class="carousel-control-next" href="#${carouselId}" role="button" data-slide="next">
                                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                <span class="sr-only">Próximo</span>
                            </a>`;
                    }
                    carouselHtml += `</div>`;
                    imagensContainer.append(carouselHtml);
                    $(`#${carouselId}`).carousel(); // Inicializa o carrossel
                } else {
                    imagensContainer.append('<p class="col-12 text-muted">Nenhuma imagem disponível.</p>');
                }
            } else {
                imagensContainer.append('<p class="col-12 text-muted">Nenhuma imagem disponível.</p>');
            }
        });
    });
</script>

<?php include 'includes/footer2.php'; ?>