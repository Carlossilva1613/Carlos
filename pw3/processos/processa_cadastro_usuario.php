<?php
require_once 'conexao.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nome = $_POST['nome'];
    $email = $_POST['email'];
    $senha = password_hash($_POST['senha'], PASSWORD_DEFAULT);

    try {
        $stmt = $conexao->prepare("INSERT INTO tb_usuario (nome, email, senha) VALUES (?, ?, ?)");
        $stmt->execute([$nome, $email, $senha]);
        header('Location: ../login.php?cadastro=sucesso');
    } catch (PDOException $e) {
        echo 'Erro: ' . $e->getMessage();
    }
}
?>