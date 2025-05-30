<?php
session_start();
require_once 'conexao.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $senha = $_POST['senha'];

    // Debug
    error_log("Tentativa de login - Email: " . $email);

    try {
        $stmt = $conexao->prepare("SELECT * FROM tb_usuario WHERE email = ?");
        $stmt->execute([$email]);
        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

        // Debug
        error_log("Dados do usuário: " . print_r($usuario, true));

        if ($usuario) {
            if (password_verify($senha, $usuario['senha'])) {
                $_SESSION['usuario_logado'] = $usuario['id_usuario'];
                $_SESSION['mensagem_sucesso'] = 'Login realizado com sucesso!';
                header('Location: ../area_usuario.php'); // Atualizado de admin.php para area_usuario.php
                exit();
            } else {
                error_log("Senha incorreta para o usuário: " . $email);
            }
        } else {
            error_log("Usuário não encontrado: " . $email);
        }

        header('Location: ../login.php?erro=login');
        exit();

    } catch (PDOException $e) {
        error_log("Erro no banco de dados: " . $e->getMessage());
        header('Location: ../login.php?erro=database');
        exit();
    }
}
?>