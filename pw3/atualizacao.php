<?php include 'includes/header.php'; ?>

<div class="container">
	<div class="card shadow-lg mt-4 mb-4">
		<div class="card-header bg-primary text-white">
			<h1 class="text-center mb-0">
				<i class="fas fa-edit"></i> Atualização de Veiculos
			</h1>
		</div>
		<div class="card-body bg-light">
			<form name="f" id="f" method="post" action="processos/processa_update.php" enctype="multipart/form-data"
				class="needs-validation" novalidate>
				<div class="row">
					<!-- Coluna da Esquerda -->
					<div class="col-md-6">
						<h4 class="mb-3 text-primary"><i class="fas fa-info-circle"></i> Informações Básicas</h4>

						<div class="form-group">
							<label for="controle"><i class="fas fa-hashtag"></i> Controle</label>
							<input type="text" name="controle" id="controle" class="form-control"
								placeholder="Número de Controle" required>
						</div>

						<div class="form-group">
							<label for="nome"><i class="fas fa-user"></i> Nome do Cliente</label>
							<input type="text" name="Nome" id="nome_cliente" class="form-control"
								placeholder="Nome Completo" required>
						</div>

						<div class="form-group">
							<label for="endereco"><i class="fas fa-map-marker-alt"></i> Endereço</label>
							<input type="text" name="endereco" id="endereco" class="form-control"
								placeholder="Endereço Completo" required>
						</div>

						<div class="form-row">
							<div class="form-group col-md-6">
								<label for="telefone"><i class="fas fa-phone"></i> Telefone</label>
								<input type="text" name="telefone" id="telefone" class="form-control"
									placeholder="(00) 00000-0000" required>
							</div>
							<div class="form-group col-md-6">
								<label for="email"><i class="fas fa-envelope"></i> Email</label>
								<input type="email" name="email" id="email" class="form-control"
									placeholder="email@exemplo.com" required>
							</div>
						</div>
					</div>

					<!-- Coluna da Direita -->
					<div class="col-md-6">
						<h4 class="mb-3 text-primary"><i class="fas fa-building"></i> Informações do Imóvel</h4>

						<div class="form-row">
							<div class="form-group col-md-6">
								<label for="valor"><i class="fas fa-dollar-sign"></i> Valor</label>
								<input type="text" name="valor" id="valor" class="form-control" placeholder="R$ 0,00"
									required>
							</div>
							<div class="form-group col-md-6">
								<label for="lote"><i class="fas fa-ruler-combined"></i> Tamanho do Lote</label>
								<input type="text" name="lote" id="lote" class="form-control" placeholder="m²" required>
							</div>
						</div>

						<div class="form-group">
							<label for="tipo"><i class="fas fa-home"></i> Tipo de Imóvel</label>
							<select name="tipo" id="tipo" class="form-control" required>
								<option value="" disabled selected>Selecione o tipo</option>
								<option value="Casa">Casa</option>
								<option value="Apartamento">Apartamento</option>
								<option value="Terreno">Terreno</option>
								<option value="Comercial">Comercial</option>
							</select>
						</div>

						<div class="form-group">
							<label for="pagamento"><i class="fas fa-handshake"></i> Tipo de Contrato</label>
							<select name="pagamento" id="pagamento" class="form-control" required>
								<option value="" disabled selected>Selecione o tipo de contrato</option>
								<option value="compra">Compra</option>
								<option value="alugar">Alugar</option>
							</select>
						</div>

						<!-- Upload de Imagens -->
						<div class="form-group">
							<label><i class="fas fa-images"></i> Imagens do Imóvel</label>
							<div class="custom-file mb-2">
								<input type="file" class="custom-file-input" id="imagens" name="imagens[]" multiple
									accept="image/*">
								<label class="custom-file-label" for="imagens">Escolher arquivos</label>
							</div>
							<div class="d-flex justify-content-between align-items-center">
								<small class="text-muted">
									<i class="fas fa-info-circle"></i> Selecione até 5 imagens (máx. 2MB cada)
								</small>
								<span id="imageCount" class="badge badge-primary">0/5</span>
							</div>
							<div id="preview" class="mt-3 row">
								<!-- Imagens existentes serão carregadas aqui -->
							</div>
						</div>
					</div>
				</div>

				<div class="row mt-4">
					<div class="col text-center">
						<button type="submit" class="btn btn-primary btn-lg px-5">
							<i class="fas fa-save"></i> Atualizar
						</button>
						<a href="index.php" class="btn btn-secondary btn-lg px-5 ml-2">
							<i class="fas fa-arrow-left"></i> Voltar
						</a>
					</div>
				</div>

				<!-- Campo oculto para ID -->
				<input type="hidden" name="id" id="id" value="">
			</form>
		</div>
	</div>
</div>

<!-- Modal para visualização de imagem -->
<div class="modal fade" id="imageModal" tabindex="-1" role="dialog">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">Visualizar Imagem</h5>
				<button type="button" class="close" data-dismiss="modal">&times;</button>
			</div>
			<div class="modal-body text-center">
				<img src="" id="modalImage" class="img-fluid">
			</div>
		</div>
	</div>
</div>

<!-- Incluindo arquivos CSS e JS -->
<link rel="stylesheet" href="css/cadastro.css">
<script src="js/imagem_preview.js"></script>

<!-- Script para validações e máscaras -->
<script>
	$(document).ready(function () {
		// Máscaras ajustadas
		$('#telefone').mask('(00) 00000-0000');
		$('#valor').mask('#.##0,00', {
			reverse: true,
			placeholder: 'R$ 0,00',
			onChange: function(value, e) {
				$(e.target).val('R$ ' + value);
			}
		});
		$('#controle').mask('0000000000');

		// Validação do formulário
		$('form').on('submit', function (e) {
			if (!this.checkValidity()) {
				e.preventDefault();
				e.stopPropagation();
			}
			$(this).addClass('was-validated');
		});

		// Carregar dados existentes (adicione esta função)
		function carregarDados(id) {
			$.ajax({
				url: 'processos/get_imovel.php',
				type: 'GET',
				data: { id: id },
				dataType: 'json',
				success: function (response) {
					if (response.erro) {
						alert('Erro: ' + response.erro);
						return;
					}

					// Preenche os campos com os dados
					$('#id').val(response.id);
					$('#controle').val(response.controle);
					$('#nome_cliente').val(response.nome);
					$('#endereco').val(response.endereco);
					$('#telefone').val(response.telefone);
					$('#email').val(response.email);
					$('#valor').val(formatarValor(response.valor));
					$('#lote').val(response.lote);
					$('#tipo').val(response.tipo);
					// Removido o campo pagamento que não existe na tabela
				},
				error: function (xhr, status, error) {
					console.error(xhr.responseText);
					alert('Erro ao carregar os dados do imóvel. Por favor, tente novamente.');
				}
			});
		}

		// Função para adicionar imagem existente
		function addExistingImage(imagemUrl) {
			const previewElement = `
			<div class="col-md-4 mb-3">
				<div class="card">
					<img src="${imagemUrl}" 
						 class="card-img-top preview-image" 
						 style="height: 200px; object-fit: cover; cursor: pointer"
						 onclick="viewImage('${imagemUrl}')"
						 alt="Imagem existente">
					<div class="card-footer p-2 text-center">
						<button type="button" class="btn btn-sm btn-danger" onclick="removeImage(this)">
							<i class="fas fa-trash"></i> Remover
						</button>
					</div>
				</div>
			</div>
		`;
			$('#preview').append(previewElement);
			updateImageCount();
		}

		 // Função para formatar valor ao carregar
		 function formatarValor(valor) {
			if (!valor) return '';
			valor = valor.replace('R$', '').trim();
			return 'R$ ' + valor;
		}

		// Carregar dados se houver ID na URL
		const urlParams = new URLSearchParams(window.location.search);
		const id = urlParams.get('id');
		if (id) {
			carregarDados(id);
		}
	});
</script>

<?php include 'includes/footer2.php'; ?>