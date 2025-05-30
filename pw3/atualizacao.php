<?php
session_start(); // Adicionar session_start se for usar sessões para mensagens, etc.
include 'includes/header.php';
require_once 'processos/conexao.php'; // Incluir conexão para buscar dados iniciais se necessário ou passar para JS

$id_veiculo_get = isset($_GET['id_veiculo']) ? intval($_GET['id_veiculo']) : 0;

?>

<div class="container">
	<div class="card shadow-lg mt-4 mb-4">
		<div class="card-header bg-warning text-white">
			<h1 class="text-center mb-0">
				<i class="fas fa-edit"></i> Atualização de Veículo
			</h1>
		</div>
		<div class="card-body bg-light">
			<form name="f_atualizacao" id="f_atualizacao" method="post" action="processos/processa_update.php"
				enctype="multipart/form-data" class="needs-validation" novalidate>

				<input type="hidden" name="id_veiculo" id="id_veiculo" value="<?php echo $id_veiculo_get; ?>">

				<div class="row">
					<!-- Coluna da Esquerda: Informações Básicas -->
					<div class="col-md-6">
						<h4 class="mb-3 text-primary"><i class="fas fa-info-circle"></i> Informações Básicas</h4>

						<div class="form-group">
							<label for="marca"><i class="fas fa-tags"></i> Marca <span
									class="text-danger">*</span></label>
							<input type="text" name="marca" id="marca" class="form-control" placeholder="Ex: Toyota"
								required>
							<div class="invalid-feedback">Informe a marca.</div>
						</div>

						<div class="form-group">
							<label for="modelo"><i class="fas fa-tag"></i> Modelo <span
									class="text-danger">*</span></label>
							<input type="text" name="modelo" id="modelo" class="form-control" placeholder="Ex: Corolla"
								required>
							<div class="invalid-feedback">Informe o modelo.</div>
						</div>

						<div class="form-row">
							<div class="form-group col-md-6">
								<label for="ano"><i class="fas fa-calendar-alt"></i> Ano <span
										class="text-danger">*</span></label>
								<input type="number" name="ano" id="ano" class="form-control" placeholder="2025"
									required>
								<div class="invalid-feedback">Informe o ano.</div>
							</div>
							<div class="form-group col-md-6">
								<label for="placa"><i class="fas fa-id-card"></i> Placa <span
										class="text-danger">*</span></label>
								<input type="text" name="placa" id="placa" class="form-control" placeholder="ABC-1234"
									required>
								<div class="invalid-feedback">Informe a placa.</div>
							</div>
						</div>
					</div>

					<!-- Coluna da Direita: Detalhes do Veículo -->
					<div class="col-md-6">
						<h4 class="mb-3 text-primary"><i class="fas fa-cogs"></i> Detalhes do Veículo</h4>

						<div class="form-group">
							<label for="cor"><i class="fas fa-palette"></i> Cor <span
									class="text-danger">*</span></label>
							<input type="text" name="cor" id="cor" class="form-control" placeholder="Ex: Prata"
								required>
							<div class="invalid-feedback">Informe a cor.</div>
						</div>

						<div class="form-group">
							<label for="valor"><i class="fas fa-dollar-sign"></i> Valor (R$) <span
									class="text-danger">*</span></label>
							<input type="text" name="valor" id="valor" class="form-control" placeholder="R$ 0,00"
								required>
							<div class="invalid-feedback">Informe o valor.</div>
						</div>

						<!-- Upload de Imagens -->
						<div class="form-group">
							<label><i class="fas fa-images"></i> Imagens do Veículo</label>
							<p class="text-muted small">Imagens existentes são exibidas abaixo. Você pode remover
								imagens existentes e/ou adicionar novas. Novas imagens substituirão as antigas se o
								limite for atingido, ou serão adicionadas.</p>
							<div class="custom-file mb-2">
								<input type="file" class="custom-file-input" id="imagens_novas" name="imagens_novas[]"
									multiple accept="image/*">
								<label class="custom-file-label" for="imagens_novas">Adicionar novas imagens...</label>
							</div>
							<div class="d-flex justify-content-between align-items-center">
								<small class="text-muted">
									<i class="fas fa-info-circle"></i> Novas: até 5 imagens (máx. 2MB cada)
								</small>
								<span id="newImageCount" class="badge badge-info">0/5</span>
							</div>
							<h6 class="mt-3">Imagens Existentes:</h6>
							<div id="preview_existentes" class="mt-2 row">
								<!-- Imagens existentes serão carregadas aqui pelo JavaScript -->
							</div>
							<h6 class="mt-3">Preview Novas Imagens:</h6>
							<div id="preview_novas" class="mt-2 row">
								<!-- Preview de novas imagens -->
							</div>
						</div>
					</div>
				</div>

				<div class="row mt-4">
					<div class="col text-center">
						<button type="submit" class="btn btn-warning btn-lg px-5">
							<i class="fas fa-save"></i> Salvar Alterações
						</button>
						<a href="consulta.php" class="btn btn-secondary btn-lg px-5 ml-2">
							<i class="fas fa-arrow-left"></i> Voltar para Consulta
						</a>
					</div>
				</div>
			</form>
		</div>
	</div>
</div>

<!-- Modal para visualização de imagem -->
<div class="modal fade" id="imageModalUpdate" tabindex="-1" role="dialog" aria-labelledby="imageModalUpdateLabel"
	aria-hidden="true">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="imageModalUpdateLabel">Visualizar Imagem</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">&times;</button>
			</div>
			<div class="modal-body text-center p-0">
				<img src="" id="modalImageUpdateSrc" class="img-fluid" alt="Preview em tamanho maior">
			</div>
		</div>
	</div>
</div>

<link rel="stylesheet" href="assets/css/cadastro.css"> <!-- Reutilizar CSS se aplicável -->

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js"></script>

<script>
	// Função para visualizar imagem no modal
	function viewImageUpdate(imageUrl) {
		$('#modalImageUpdateSrc').attr('src', imageUrl);
		$('#imageModalUpdate').modal('show');
	}

	// Função para remover imagem existente (apenas no frontend, backend fará a exclusão)
	function removeExistingImage(buttonElement, imagemId, imagemNome) {
		if (confirm('Tem certeza que deseja remover esta imagem? Esta ação não poderá ser desfeita imediatamente aqui.')) {
			// Adiciona um input hidden para marcar a imagem para remoção
			$('<input>').attr({
				type: 'hidden',
				name: 'imagens_remover[]',
				value: imagemId // ou imagemNome, dependendo de como o backend identificará
			}).appendTo('#f_atualizacao');

			$(buttonElement).closest('.col-md-4').remove();
			// Adicionar lógica para atualizar contagem de imagens existentes se necessário
		}
	}

	$(document).ready(function () {
		$('#valor').mask('#.##0,00', { reverse: true, placeholder: 'R$ 0,00' });
		// Adicionar outras máscaras se necessário (ex: placa)

		// Validação Bootstrap
		(function () {
			'use strict';
			window.addEventListener('load', function () {
				var forms = document.getElementsByClassName('needs-validation');
				Array.prototype.filter.call(forms, function (form) {
					form.addEventListener('submit', function (event) {
						if (form.checkValidity() === false) {
							event.preventDefault();
							event.stopPropagation();
						}
						form.classList.add('was-validated');
					}, false);
				});
			}, false);
		})();

		// Carregar dados do veículo para atualização
		const idVeiculo = $('#id_veiculo').val();
		if (idVeiculo && idVeiculo > 0) {
			$.ajax({
				url: 'processos/get_veiculo_details.php', // Endpoint para buscar dados do veículo
				type: 'GET',
				data: { id_veiculo: idVeiculo },
				dataType: 'json',
				success: function (response) {
					if (response.erro) {
						alert('Erro: '.response.erro);
						// Redirecionar ou mostrar mensagem mais elaborada
						window.location.href = 'consulta.php';
						return;
					}
					if (response.veiculo) {
						const v = response.veiculo;
						$('#marca').val(v.marca);
						$('#modelo').val(v.modelo);
						$('#ano').val(v.ano);
						$('#placa').val(v.placa);
						$('#cor').val(v.cor);
						// Formatar valor para R$ ao carregar
						let valorFormatado = parseFloat(v.preco).toLocaleString('pt-BR', { style: 'currency', currency: 'BRL' });
						$('#valor').val(valorFormatado.replace('R$', '').trim()); // Remove R$ para a máscara aplicar corretamente
						$('#valor').trigger('input'); // Para a máscara reformatar se necessário

						// Carregar imagens existentes
						if (response.imagens && response.imagens.length > 0) {
							const previewExistentes = $('#preview_existentes');
							previewExistentes.empty(); // Limpa previews anteriores
							response.imagens.forEach(function (img) {
								const imgHtml = `
									<div class="col-md-4 mb-3">
										<div class="card">
											<img src="uploads/${img.caminho}" 
												 class="card-img-top preview-image" 
												 style="height: 150px; object-fit: cover; cursor: pointer;"
												 onclick="viewImageUpdate('uploads/${img.caminho}')"
												 alt="Imagem existente">
											<div class="card-footer p-1 text-center">
												<button type="button" class="btn btn-sm btn-danger btn-block" 
														onclick="removeExistingImage(this, ${img.id_imagem}, '${img.caminho}')">
													<i class="fas fa-trash"></i> Remover
												</button>
											</div>
										</div>
									</div>`;
								previewExistentes.append(imgHtml);
							});
						} else {
							$('#preview_existentes').html('<p class="col-12 text-muted">Nenhuma imagem cadastrada para este veículo.</p>');
						}
					} else {
						alert('Veículo não encontrado.');
						window.location.href = 'consulta.php';
					}
				},
				error: function (xhr, status, error) {
					console.error("Erro AJAX:", xhr.responseText, status, error);
					alert('Erro ao carregar os dados do veículo. Verifique o console para mais detalhes.');
					window.location.href = 'consulta.php';
				}
			});
		} else {
			alert('ID do veículo não fornecido ou inválido.');
			window.location.href = 'consulta.php'; // Redireciona se não houver ID
		}

		// Preview para novas imagens
		const MAX_IMAGES_NEW = 5;
		let newImageFiles = [];

		$('#imagens_novas').on('change', function (event) {
			const files = Array.from(event.target.files);
			const previewNovas = $('#preview_novas');

			// Limitar o número de novas imagens
			if (newImageFiles.length + files.length > MAX_IMAGES_NEW) {
				alert(`Você pode selecionar no máximo ${MAX_IMAGES_NEW} novas imagens.`);
				// Limpar a seleção de arquivos para evitar confusão
				$(this).val('');
				return;
			}

			files.forEach(file => {
				if (file.size > 2 * 1024 * 1024) { // 2MB
					alert(`O arquivo ${file.name} é muito grande (máx. 2MB).`);
					return; // Pula este arquivo
				}
				if (!file.type.startsWith('image/')) {
					alert(`O arquivo ${file.name} não é uma imagem válida.`);
					return; // Pula este arquivo
				}

				newImageFiles.push(file); // Adiciona à lista de arquivos a serem enviados

				const reader = new FileReader();
				reader.onload = function (e) {
					const imgHtml = `
						<div class="col-md-4 mb-3 new-image-preview-item">
							<div class="card">
								<img src="${e.target.result}" 
									 class="card-img-top preview-image" 
									 style="height: 150px; object-fit: cover; cursor: pointer;"
									 onclick="viewImageUpdate('${e.target.result}')"
									 alt="Nova imagem: ${file.name}">
								<div class="card-footer p-1 text-center">
									<button type="button" class="btn btn-sm btn-warning btn-block btn-remove-new-preview">
										<i class="fas fa-times-circle"></i> Cancelar
									</button>
								</div>
							</div>
						</div>`;
					previewNovas.append(imgHtml);
				}
				reader.readAsDataURL(file);
			});
			updateNewImageCount();
			// Atualizar o DataTransfer para o input (importante para o envio do form)
			// Esta parte é complexa e pode não ser necessária se o backend processar 'imagens_novas[]' diretamente
			// Por simplicidade, o backend pegará os arquivos de 'imagens_novas[]'
		});

		$(document).on('click', '.btn-remove-new-preview', function () {
			const itemToRemove = $(this).closest('.new-image-preview-item');
			const indexToRemove = itemToRemove.index(); // Encontra o índice do item

			itemToRemove.remove(); // Remove o preview do DOM
			newImageFiles.splice(indexToRemove, 1); // Remove o arquivo da lista 'newImageFiles'

			// Atualiza o input de arquivos para refletir a remoção (mais complexo, pode ser simplificado)
			// Uma forma mais simples é apenas gerenciar 'newImageFiles' e o backend usa essa lista.
			// Para o input file, é mais fácil limpar e pedir para o usuário re-selecionar se a edição for muito dinâmica.
			// Ou, ao submeter, o backend ignora os arquivos que foram removidos do preview se você enviar um array de nomes a manter.
			// Por ora, a remoção é apenas visual e da lista 'newImageFiles'. O input 'imagens_novas' ainda terá todos os arquivos selecionados inicialmente.
			// Para uma solução robusta aqui, seria necessário reconstruir o objeto DataTransfer do input.
			// A maneira mais simples é que o backend processe apenas os arquivos que não foram "cancelados" (se você enviar essa informação).
			// Ou, mais fácil ainda, o usuário re-seleciona.

			// Se você quer que o input file reflita a remoção, você teria que limpar o input e
			// reconstruir o FileList, o que é complicado.
			// A abordagem mais comum é deixar o backend lidar com isso, ou o usuário re-seleciona.
			// Vamos apenas atualizar a contagem.
			updateNewImageCount();
		});


		function updateNewImageCount() {
			$('#newImageCount').text(`${newImageFiles.length}/${MAX_IMAGES_NEW}`);
		}
		// Atualiza o nome do arquivo no label do custom-file-input
		$('#imagens_novas').on('change', function () {
			let fileNames = [];
			for (let i = 0; i < $(this)[0].files.length; i++) {
				fileNames.push($(this)[0].files[i].name);
			}
			if (fileNames.length > 0) {
				$(this).next('.custom-file-label').html(fileNames.join(', '));
			} else {
				$(this).next('.custom-file-label').html('Adicionar novas imagens...');
			}
		});


	});
</script>

<?php include 'includes/footer2.php'; ?>