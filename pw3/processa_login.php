<?php
session_start();
require_once 'conexao.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $senha = $_POST['senha'];

    try {
        $stmt = $conexao->prepare("SELECT * FROM usuarios WHERE email = ?");
        $stmt->execute([$email]);
        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($usuario && password_verify($senha, $usuario['senha'])) {
            $_SESSION['usuario_logado'] = $usuario['id'];
            $_SESSION['mensagem_sucesso'] = 'Login realizado com sucesso!';
            header('Location: ../admin.php'); // Redirecionar para a página de administração
            exit();
        } else {
            header('Location: ../login.php?erro=login');
            exit();
        }
    } catch (PDOException $e) {
        echo 'Erro: ' . $e->getMessage();
    }
}
?>