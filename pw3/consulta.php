<?php
session_start();
include 'includes/header.php';
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
                            <label for="tipo"><i class="fas fa-filter"></i> Filtrar por Tipo de Veículo:</label>
                            <select name="tipo_contrato" id="tipo_contrato" class="form-control">
                                <option value="">Todos</option>
                                <option value="A" <?php echo (isset($_GET['tipo_contrato']) && $_GET['tipo_contrato'] == 'A') ? 'selected' : ''; ?>>Carro</option>
                                <option value="C" <?php echo (isset($_GET['tipo_contrato']) && $_GET['tipo_contrato'] == 'C') ? 'selected' : ''; ?>>Moto</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group mb-md-0">
                            <label for="busca_controle"><i class="fas fa-hashtag"></i> Número de Controle:</label>
                            <input type="text" class="form-control" id="busca_controle" name="busca_controle"
                                placeholder="Digite o número de controle"
                                value="<?php echo isset($_GET['busca_controle']) ? htmlspecialchars($_GET['busca_controle']) : ''; ?>">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group mb-md-0">
                            <button type="submit" class="btn btn-primary btn-block">
                                <i class="fas fa-search"></i> Buscar
                            </button>
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

        // Filtro por tipo de contrato
        if (isset($_GET['tipo_contrato']) && !empty($_GET['tipo_contrato'])) {
            $where[] = "tipo_contrato = :tipo_contrato";
            $params[':tipo_contrato'] = $_GET['tipo_contrato'];
        }

        // Filtro por número de controle
        if (isset($_GET['busca_controle']) && !empty($_GET['busca_controle'])) {
            $where[] = "controle LIKE :controle";
            $params[':controle'] = '%' . $_GET['busca_controle'] . '%';
        }

        $whereClause = !empty($where) ? " WHERE " . implode(" AND ", $where) : "";

        $sql = "SELECT id_veiculo, id_usuario, titulo, descricao, preco, criado_em
                FROM tb_veiculo" . $whereClause . " 
                ORDER BY id_veiculo";

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
                <table class="table table-hover table-striped">
                    <thead class="thead-dark">
                        <tr>
                            <th><i class="fas fa-hashtag"></i> id_veiculo</th>
                            <th><i class="fas fa-user"></i> id_usuario</th>
                            <th><i class="fas fa-map-marker-alt"></i> titulo</th>
                            <th><i class="fas fa-phone"></i> descricao</th>
                            <th><i class="fas fa-envelope"></i> criado_em</th>
                            <th><i class="fas fa-dollar-sign"></i> preco</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($registros as $registro): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($registro['id_veiculo']); ?></td>
                                <td><?php echo htmlspecialchars($registro['id_usuario']); ?></td>
                                <td><?php echo htmlspecialchars($registro['titulo']); ?></td>
                                <td><?php echo htmlspecialchars($registro['descricao']); ?></td>
                                <td><?php echo htmlspecialchars($registro['criado_em']); ?></td>
                                <td>R$ <?php echo number_format(floatval($registro['preco']), 2, ',', '.'); ?></td>
                                <td>
                                    <span class="badge badge-<?php echo $registro['tipo'] == 'Carro' ? 'info' : 'success'; ?>">
                                        <?php echo htmlspecialchars($registro['tipo']); ?>
                                    </span>
                                </td>
                                <td>
                                    <?php echo ($registro['tipo_contrato'] == 'C') ? 'Carro' : 'Moto'; ?>
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="atualizacao.php?id=<?php echo $registro['id']; ?>" class="btn btn-warning btn-sm"
                                            title="Editar">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <a href="exclusao.php?controle=<?php echo urlencode($registro['controle']); ?>"
                                            class="btn btn-danger btn-sm" title="Excluir">
                                            <i class="fas fa-trash"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <!-- Botões de Exportação -->
            <div class="row mt-3">
                <div class="col-md-6">
                    <button type="button" class="btn btn-success mr-2" onclick="exportarParaExcel()">
                        <i class="fas fa-file-excel"></i> Exportar para Excel
                    </button>
                    <button type="button" class="btn btn-danger" onclick="exportarParaPDF()">
                        <i class="fas fa-file-pdf"></i> Exportar para PDF
                    </button>
                </div>
                <div class="col-md-6 text-right">
                    <a href="index.php" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Voltar
                    </a>
                </div>
            </div>
            <?php
        } else {
            ?>
            <div class="alert alert-warning">
                <i class="fas fa-exclamation-triangle"></i>
                Nenhum registro encontrado com os filtros selecionados.
            </div>
            <a href="index.php" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Voltar
            </a>
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
</style>

<!-- JavaScript para Exportação -->
<script>
    function exportarParaExcel() {
        // Implementar a exportação para Excel
        alert('Funcionalidade de exportação para Excel em desenvolvimento');
    }

    function exportarParaPDF() {
        // Implementar a exportação para PDF
        alert('Funcionalidade de exportação para PDF em desenvolvimento');
    }

    // Máscara para o campo de controle
    $(document).ready(function () {
        $('#busca_controle').mask('0000000000');
    });

    // Persistir valores dos filtros após submit
    $(document).ready(function () {
        const urlParams = new URLSearchParams(window.location.search);

        if (urlParams.has('tipo_contrato')) {
            $('#tipo_contrato').val(urlParams.get('tipo_contrato'));
        }

        if (urlParams.has('busca_controle')) {
            $('#busca_controle').val(urlParams.get('busca_controle'));
        }

        // Limpar filtros
        $('.btn-limpar-filtros').click(function (e) {
            e.preventDefault();
            window.location.href = 'consulta.php';
        });
    });
</script>

<?php include 'includes/footer2.php'; ?>