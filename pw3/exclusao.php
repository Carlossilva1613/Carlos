<?php
include 'includes/header.php';
require_once 'processos/conexao.php';

// Validação do ID do veículo
if (!isset($_GET['id_veiculo']) || empty($_GET['id_veiculo']) || !is_numeric($_GET['id_veiculo'])) {
    echo '<div class="container mt-4">
            <div class="alert alert-danger">
                <i class="fas fa-exclamation-triangle"></i> 
                ID do veículo não fornecido ou inválido!
            </div>
            <a href="consulta.php" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Voltar
            </a>
          </div>';
    include 'includes/footer2.php'; // Adicionar footer para consistência
    exit();
}

$id_veiculo = intval($_GET['id_veiculo']);

try {
    // Busca os dados do veículo
    $stmt = $conexao->prepare("SELECT id_veiculo, marca, modelo, placa FROM tb_veiculo WHERE id_veiculo = ?");
    $stmt->execute([$id_veiculo]);
    $veiculo = $stmt->fetch();

    if (!$veiculo) {
        throw new Exception("Veículo não encontrado!");
    }
    ?>

    <div class="container mt-4">
        <div class="card shadow">
            <div class="card-header bg-danger text-white">
                <h3><i class="fas fa-trash"></i> Confirmar Exclusão do Veículo</h3>
            </div>
            <div class="card-body">
                <h4>Deseja realmente excluir este veículo?</h4>

                <div class="alert alert-danger mt-3">
                    <i class="fas fa-exclamation-triangle"></i>
                    <strong>ATENÇÃO!</strong> Esta ação não poderá ser desfeita após a confirmação.
                </div>

                <div class="alert alert-warning mt-3">
                    <strong>ID:</strong> <?php echo htmlspecialchars($veiculo['id_veiculo']); ?><br>
                    <strong>Marca:</strong> <?php echo htmlspecialchars($veiculo['marca']); ?><br>
                    <strong>Modelo:</strong> <?php echo htmlspecialchars($veiculo['modelo']); ?><br>
                    <strong>Placa:</strong> <?php echo htmlspecialchars($veiculo['placa']); ?>
                </div>

                <form id="formExclusao" action="processos/processa_exclusao.php" method="POST" class="mt-4">
                    <input type="hidden" name="id_veiculo" value="<?php echo htmlspecialchars($id_veiculo); ?>">

                    <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#modalConfirmacao">
                        <i class="fas fa-trash"></i> Confirmar Exclusão
                    </button>

                    <a href="consulta.php" class="btn btn-secondary">
                        <i class="fas fa-times"></i> Cancelar
                    </a>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal de Confirmação -->
    <div class="modal fade" id="modalConfirmacao" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title">
                        <i class="fas fa-exclamation-triangle"></i> Confirmar Exclusão
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Fechar"></button>
                </div>
                <div class="modal-body">
                    <div class="text-center mb-3">
                        <i class="fas fa-trash fa-3x text-danger mb-3"></i>
                        <h4>Tem certeza que deseja excluir este veículo?</h4>
                    </div>

                    <div class="alert alert-danger">
                        <i class="fas fa-exclamation-circle"></i>
                        Esta ação <strong>não poderá</strong> ser desfeita!
                    </div>

                    <div class="alert alert-warning">
                        <strong>Detalhes do veículo:</strong><br>
                        Marca: <?php echo htmlspecialchars($veiculo['marca']); ?><br>
                        Modelo: <?php echo htmlspecialchars($veiculo['modelo']); ?><br>
                        Placa: <?php echo htmlspecialchars($veiculo['placa']); ?>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times"></i> Cancelar
                    </button>
                    <button type="button" class="btn btn-danger" onclick="submeterFormulario()">
                        <i class="fas fa-trash"></i> Excluir Definitivamente
                    </button>
                </div>
            </div>
        </div>
    </div>

    <?php
} catch (Exception $e) {
    echo '<div class="container mt-4">
            <div class="alert alert-danger">
                <i class="fas fa-exclamation-circle"></i> 
                Erro: ' . htmlspecialchars($e->getMessage()) . '
            </div>
            <a href="consulta.php" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Voltar
            </a>
          </div>';
    include 'includes/footer2.php'; // Garantir que o footer seja incluído aqui também
    exit(); // Adicionar exit após o footer em caso de erro
}

include 'includes/footer2.php';
?>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Verifica se o modal está funcionando
        var myModal = new bootstrap.Modal(document.getElementById('modalConfirmacao'));
    });

    function submeterFormulario() {
        try {
            const form = document.getElementById('formExclusao');
            if (form) {
                form.submit();
            } else {
                alert('Erro: Formulário não encontrado');
            }
        } catch (e) {
            console.error('Erro ao submeter:', e);
            alert('Ocorreu um erro ao processar a exclusão');
        }
    }
</script>