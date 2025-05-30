<?php
include 'includes/header.php';
require_once 'processos/conexao.php';

// Validação do número de controle
if (!isset($_GET['controle']) || empty($_GET['controle'])) {
    echo '<div class="container mt-4">
            <div class="alert alert-danger">
                <i class="fas fa-exclamation-triangle"></i> 
                Número de controle não fornecido!
            </div>
            <a href="consulta.php" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Voltar
            </a>
          </div>';
    exit();
}

$controle = $_GET['controle'];

try {
    // Busca os dados do imóvel
    $stmt = $conexao->prepare("SELECT * FROM imoveis WHERE controle = ?");
    $stmt->execute([$controle]);
    $imovel = $stmt->fetch();

    if (!$imovel) {
        throw new Exception("Imóvel não encontrado!");
    }
    ?>

    <div class="container mt-4">
        <div class="card shadow">
            <div class="card-header bg-danger text-white">
                <h3><i class="fas fa-trash"></i> Confirmar Exclusão</h3>
            </div>
            <div class="card-body">
                <h4>Deseja realmente excluir este registro?</h4>

                <div class="alert alert-danger mt-3">
                    <i class="fas fa-exclamation-triangle"></i>
                    <strong>ATENÇÃO!</strong> Esta ação não poderá ser desfeita após a confirmação.
                </div>

                <div class="alert alert-warning mt-3">
                    <strong>Controle:</strong> <?php echo htmlspecialchars($imovel['controle']); ?><br>
                    <strong>Nome:</strong> <?php echo htmlspecialchars($imovel['nome']); ?><br>
                    <strong>Endereço:</strong> <?php echo htmlspecialchars($imovel['endereco']); ?>
                </div>

                <form id="formExclusao" action="processos/processa_exclusao.php" method="POST" class="mt-4">
                    <input type="hidden" name="controle" value="<?php echo htmlspecialchars($controle); ?>">

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
                        <h4>Tem certeza que deseja excluir este registro?</h4>
                    </div>

                    <div class="alert alert-danger">
                        <i class="fas fa-exclamation-circle"></i>
                        Esta ação <strong>não poderá</strong> ser desfeita!
                    </div>

                    <div class="alert alert-warning">
                        <strong>Detalhes do registro:</strong><br>
                        Controle: <?php echo htmlspecialchars($imovel['controle']); ?><br>
                        Nome: <?php echo htmlspecialchars($imovel['nome']); ?>
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