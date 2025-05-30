<?php
session_start();
require_once 'conexao.php';

if (!isset($_POST['controle']) || empty($_POST['controle'])) {
    $_SESSION['erro'] = "Número de controle não fornecido para exclusão";
    header('Location: ../consulta.php');
    exit();
}

$controle = filter_var($_POST['controle'], FILTER_SANITIZE_NUMBER_INT);

try {
    // Tenta excluir o registro diretamente
    $stmt = $conexao->prepare("DELETE FROM imoveis WHERE controle = ?");
    $resultado = $stmt->execute([$controle]);

    if ($resultado && $stmt->rowCount() > 0) {
        $_SESSION['sucesso'] = "Registro excluído com sucesso!";
    } else {
        $_SESSION['erro'] = "Nenhum registro foi encontrado para exclusão";
    }

} catch (PDOException $e) {
    error_log("Erro na exclusão - Controle: $controle - " . $e->getMessage());
    $_SESSION['erro'] = "Erro ao excluir o registro. Por favor, tente novamente.";
}

header('Location: ../consulta.php');
exit();
?>