<?php
require_once 'conexao.php';

if (isset($_GET['imovel_id'])) {
    try {
        $stmt = $conexao->prepare("SELECT * FROM imagens WHERE imovel_id = ?");
        $stmt->execute([$_GET['imovel_id']]);
        $imagens = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode($imagens);
    } catch (PDOException $e) {
        echo json_encode(['erro' => $e->getMessage()]);
    }
}
?>