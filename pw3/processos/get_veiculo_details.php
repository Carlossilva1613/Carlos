<?php
header('Content-Type: application/json');
require_once __DIR__ . '/conexao.php'; // Ajuste o caminho se necessário

$response = ['erro' => null, 'veiculo' => null, 'imagens' => []];

if (!isset($_GET['id_veiculo']) || !is_numeric($_GET['id_veiculo'])) {
    $response['erro'] = 'ID do veículo inválido ou não fornecido.';
    echo json_encode($response);
    exit;
}

$id_veiculo = intval($_GET['id_veiculo']);

try {
    // Buscar dados do veículo
    // Adicionado JOIN com tb_usuario para pegar o nome do vendedor, caso necessário no futuro,
    // mas o formulário atual não o utiliza diretamente para preenchimento.
    // Se não for usar nome_vendedor aqui, pode simplificar o SELECT.
    $sql_veiculo = "SELECT v.* 
                    FROM tb_veiculo v
                    WHERE v.id_veiculo = :id_veiculo";
    $stmt_veiculo = $conexao->prepare($sql_veiculo);
    $stmt_veiculo->bindParam(':id_veiculo', $id_veiculo, PDO::PARAM_INT);
    $stmt_veiculo->execute();
    $veiculo_data = $stmt_veiculo->fetch(PDO::FETCH_ASSOC);

    if (!$veiculo_data) {
        $response['erro'] = 'Veículo não encontrado.';
        echo json_encode($response);
        exit;
    }
    $response['veiculo'] = $veiculo_data;

    // Buscar imagens do veículo
    $sql_imagens = "SELECT id_imagem, caminho FROM tb_imagem_veiculo WHERE id_veiculo = :id_veiculo ORDER BY id_imagem";
    $stmt_imagens = $conexao->prepare($sql_imagens);
    $stmt_imagens->bindParam(':id_veiculo', $id_veiculo, PDO::PARAM_INT);
    $stmt_imagens->execute();
    $imagens_data = $stmt_imagens->fetchAll(PDO::FETCH_ASSOC);

    if ($imagens_data) {
        $response['imagens'] = $imagens_data;
    }

} catch (PDOException $e) {
    $response['erro'] = 'Erro no banco de dados: ' . $e->getMessage();
    // Em produção, logar o erro em vez de expô-lo diretamente
    // error_log('Erro em get_veiculo_details.php: ' . $e->getMessage());
    // $response['erro'] = 'Ocorreu um erro ao buscar os dados do veículo.';
} catch (Exception $e) {
    $response['erro'] = 'Erro geral: ' . $e->getMessage();
}

echo json_encode($response);
?>