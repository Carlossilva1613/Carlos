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
                    <form action="processos/processa_cadastro_usuario.php" method="POST">
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
                        <button type="submit" class="btn btn-primary btn-block">Criar Conta</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
include 'includes/footer2.php';
?>