<?php
include 'includes/header.php';
?>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h3 class="mb-0">Criar Conta</h3>
                </div>
                <div class="card-body">
                    <form id="formCadastroUsuario" action="processos/processa_cadastro_usuario.php" method="POST">
                        <div class="form-group">
                            <label for="nome">Nome</label>
                            <input type="text" name="nome" id="nome" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="email" name="email" id="email" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="senha">Senha</label>
                            <input type="password" name="senha" id="senha" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="confirmar_senha">Confirmar Senha</label>
                            <input type="password" name="confirmar_senha" id="confirmar_senha" class="form-control"
                                required>
                            <div class="invalid-feedback" id="senhaError" style="display: none;">
                                As senhas não coincidem.
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary btn-block">Criar Conta</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const form = document.getElementById('formCadastroUsuario');
        const senhaInput = document.getElementById('senha');
        const confirmarSenhaInput = document.getElementById('confirmar_senha');
        const senhaErrorDiv = document.getElementById('senhaError');

        form.addEventListener('submit', function (event) {
            if (senhaInput.value !== confirmarSenhaInput.value) {
                event.preventDefault(); // Impede o envio do formulário
                confirmarSenhaInput.classList.add('is-invalid');
                senhaErrorDiv.style.display = 'block';
            } else {
                confirmarSenhaInput.classList.remove('is-invalid');
                senhaErrorDiv.style.display = 'none';
            }
        });

        // Opcional: Validação em tempo real ao sair do campo "Confirmar Senha"
        confirmarSenhaInput.addEventListener('blur', function () {
            if (senhaInput.value !== confirmarSenhaInput.value && confirmarSenhaInput.value !== '') {
                confirmarSenhaInput.classList.add('is-invalid');
                senhaErrorDiv.style.display = 'block';
            } else {
                confirmarSenhaInput.classList.remove('is-invalid');
                senhaErrorDiv.style.display = 'none';
            }
        });
        senhaInput.addEventListener('input', function () {
            if (confirmarSenhaInput.value !== '' && senhaInput.value !== confirmarSenhaInput.value) {
                confirmarSenhaInput.classList.add('is-invalid');
                senhaErrorDiv.style.display = 'block';
            } else if (confirmarSenhaInput.value !== '' && senhaInput.value === confirmarSenhaInput.value) {
                confirmarSenhaInput.classList.remove('is-invalid');
                senhaErrorDiv.style.display = 'none';
            }
        });
    });
</script>

<?php
include 'includes/footer2.php';
?>