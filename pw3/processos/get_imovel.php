<?php
// processos/get_imovel.php

// Inclui arquivo de conexão com o banco de dados
require_once 'conexao.php';

// Verifica se foi enviado um ID
if (!isset($_GET['id']) || empty($_GET['id'])) {
    echo json_encode(['error' => 'ID não fornecido']);
    exit;
}

try {
    // Pega o ID do imóvel
    $id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);

    // Simplificando a consulta SQL
    $sql = "SELECT * FROM tb_veiculo WHERE id = :id LIMIT 1";
    $stmt = $conexao->prepare($sql);
    $stmt->execute(['id' => $id]);

    // Busca os dados
    $imovel = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$imovel) {
        http_response_code(404);
        echo json_encode(['error' => 'Imóvel não encontrado']);
        exit;
    }

    // Formatando o valor monetário
    if (isset($imovel['valor']) && !empty($imovel['valor'])) {
        $imovel['valor'] = 'R$ ' . number_format($imovel['valor'], 2, ',', '.');
    }

    // Retorna os dados em formato JSON
    header('Content-Type: application/json');
    echo json_encode($imovel);

} catch (Exception $e) {
    // Em caso de erro, retorna uma mensagem de erro em formato JSON
    http_response_code(500);
    echo json_encode(['erro' => $e->getMessage()]);
}
?>