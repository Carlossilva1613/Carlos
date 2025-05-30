<?php
session_start();
include 'includes/header.php';
?>

<div class="container">
  <?php if (isset($_GET['sucesso']) && isset($_SESSION['cadastro_sucesso'])): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
      <h4 class="alert-heading"><i class="fas fa-check-circle"></i> Cadastro realizado com sucesso!</h4>
      <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
      </button>
      <hr>
      <div class="card">
        <div class="card-header bg-success text-white">
          <h5 class="mb-0">Dados do Veículo Cadastrado</h5>
        </div>
        <div class="card-body">
          <div class="row">
            <div class="col-md-6">

              <p><strong><i class="fas fa-tags"></i> Marca:</strong>
                <?= htmlspecialchars($_SESSION['cadastro_sucesso']['marca']); ?></p>
              <p><strong><i class="fas fa-tag"></i> Modelo:</strong>
                <?= htmlspecialchars($_SESSION['cadastro_sucesso']['modelo']); ?></p>
              <p><strong><i class="fas fa-calendar-alt"></i> Ano:</strong>
                <?= htmlspecialchars($_SESSION['cadastro_sucesso']['ano']); ?></p>
              <p><strong><i class="fas fa-id-card"></i> Placa:</strong>
                <?= htmlspecialchars($_SESSION['cadastro_sucesso']['placa']); ?></p>
            </div>
            <div class="col-md-6">
              <p><strong><i class="fas fa-palette"></i> Cor:</strong>
                <?= htmlspecialchars($_SESSION['cadastro_sucesso']['cor']); ?></p>
              <p><strong><i class="fas fa-dollar-sign"></i> Valor:</strong> R$
                <?= number_format((float) $_SESSION['cadastro_sucesso']['valor'], 2, ',', '.'); ?>
              </p>
            </div>
          </div>
        </div>
        <div class="card-footer text-center">
          <a href="cadastro_veiculo.php" class="btn btn-primary">
            <i class="fas fa-plus"></i> Novo Veículo
          </a>
          <a href="consulta_veiculo.php" class="btn btn-info">
            <i class="fas fa-search"></i> Ver Todos
          </a>
        </div>
      </div>
    </div>
    <?php unset($_SESSION['cadastro_sucesso']); endif; ?>

  <?php if (isset($_SESSION['erro'])): ?>
    <div class="alert alert-danger alert-dismissible fade show mt-3" role="alert">
      <h4 class="alert-heading"><i class="fas fa-exclamation-triangle"></i> Erro no Cadastro!</h4>
      <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
      </button>
      <hr>
      <p><?= htmlspecialchars($_SESSION['erro']); ?></p>
    </div>
    <?php unset($_SESSION['erro']); endif; ?>

  <div class="card shadow-lg mt-4 mb-4">
    <div class="card-header bg-primary text-white">
      <h1 class="text-center mb-0">
        <i class="fas fa-car"></i> Cadastro de Veículos
      </h1>
    </div>
    <div class="card-body bg-light">
      <form id="f" name="f" method="post" action="processos/processa_cadastro.php" enctype="multipart/form-data"
        class="needs-validation" novalidate>
        <div class="row">
          <!-- Informações Básicas -->
          <div class="col-md-6">
            <h4 class="mb-3 text-primary"><i class="fas fa-info-circle"></i> Informações Básicas</h4>


            <div class="form-group">
              <label for="marca"><i class="fas fa-tags"></i> Marca <span class="text-danger">*</span></label>
              <input type="text" name="marca" id="marca" class="form-control" placeholder="Ex: Toyota" required>
              <div class="invalid-feedback">Informe a marca.</div>
            </div>

            <div class="form-group">
              <label for="modelo"><i class="fas fa-tag"></i> Modelo <span class="text-danger">*</span></label>
              <input type="text" name="modelo" id="modelo" class="form-control" placeholder="Ex: Corolla" required>
              <div class="invalid-feedback">Informe o modelo.</div>
            </div>

            <div class="form-row">
              <div class="form-group col-md-6">
                <label for="ano"><i class="fas fa-calendar-alt"></i> Ano <span class="text-danger">*</span></label>
                <input type="number" name="ano" id="ano" class="form-control" placeholder="2025" required>
                <div class="invalid-feedback">Informe o ano.</div>
              </div>
              <div class="form-group col-md-6">
                <label for="placa"><i class="fas fa-id-card"></i> Placa <span class="text-danger">*</span></label>
                <input type="text" name="placa" id="placa" class="form-control" placeholder="ABC-1234" required>
                <div class="invalid-feedback">Informe a placa.</div>
              </div>
            </div>
          </div>

          <!-- Detalhes do Veículo -->
          <div class="col-md-6">
            <h4 class="mb-3 text-primary"><i class="fas fa-cogs"></i> Detalhes do Veículo</h4>

            <div class="form-group">
              <label for="cor"><i class="fas fa-palette"></i> Cor <span class="text-danger">*</span></label>
              <input type="text" name="cor" id="cor" class="form-control" placeholder="Ex: Prata" required>
              <div class="invalid-feedback">Informe a cor.</div>
            </div>

            <div class="form-group">
              <label for="valor"><i class="fas fa-dollar-sign"></i> Valor (R$) <span
                  class="text-danger">*</span></label>
              <input type="text" name="valor" id="valor" class="form-control" placeholder="0,00" required>
              <div class="invalid-feedback">Informe o valor.</div>
            </div>

            <!-- <div class="form-group">
              <label for="tipo_veiculo"><i class="fas fa-car-side"></i> Tipo de Veículo <span class="text-danger">*</span></label>
              <select name="tipo_veiculo" id="tipo_veiculo" class="form-control" required>
                <option value="" disabled selected>Selecione</option>
                <option value="Carro">Carro</option>
                <option value="Moto">Moto</option>
                <option value="Caminhão">Caminhão</option>
                <option value="Van">Van</option>
              </select>
              <div class="invalid-feedback">Selecione o tipo.</div>
            </div> -->

            <!-- Upload de Imagens -->
            <div class="form-group">
              <label><i class="fas fa-images"></i> Imagens do Veículo</label>
              <div class="custom-file mb-2">
                <input type="file" class="custom-file-input" id="imagens" name="imagens[]" multiple accept="image/*">
                <label class="custom-file-label" for="imagens">Escolher arquivos</label>
              </div>
              <div class="d-flex justify-content-between align-items-center">
                <small class="text-muted"><i class="fas fa-info-circle"></i> Até 5 imagens (máx. 2MB cada)</small>
                <span id="imageCount" class="badge badge-primary">0/5</span>
              </div>
              <div id="preview" class="mt-3 row"></div>
            </div>
          </div>
        </div>

        <div class="row mt-4">
          <div class="col text-center">
            <button type="submit" class="btn btn-primary btn-lg px-5">
              <i class="fas fa-save"></i> Cadastrar
            </button>
            <a href="index.php" class="btn btn-secondary btn-lg px-5 ml-2">
              <i class="fas fa-arrow-left"></i> Voltar
            </a>
          </div>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Modal de visualização de imagem -->
<div class="modal fade" id="imageModal" tabindex="-1" role="dialog">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Visualizar Imagem</h5>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body text-center p-0">
        <img src="" id="modalImage" class="img-fluid" alt="Preview em tamanho maior">
      </div>
    </div>
  </div>
</div>

<link rel="stylesheet" href="assets/css/cadastro.css">
<script src="assets/js/form-validation.js"></script>
<script src="assets/js/alerts.js"></script>
<script src="assets/js/imagem_preview.js"></script>

<?php include 'includes/footer2.php'; ?>