<?php
session_start();
require_once 'conexao.php';

// Limpar mensagens de sessão anteriores para evitar confusão
unset($_SESSION['sucesso']);
unset($_SESSION['erro']);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['id_veiculo']) && !empty($_POST['id_veiculo']) && is_numeric($_POST['id_veiculo'])) {
        $id_veiculo = intval($_POST['id_veiculo']);
        $uploaddir = __DIR__ . '/../uploads/'; // Caminho para a pasta de uploads
        $nome_veiculo_excluido = "Veículo (ID: {$id_veiculo})"; // Valor padrão caso não encontre o nome

        try {
            // Buscar nome do veículo antes de deletar para usar na mensagem
            $stmt_nome_veiculo = $conexao->prepare("SELECT marca, modelo FROM tb_veiculo WHERE id_veiculo = :id_veiculo");
            $stmt_nome_veiculo->bindParam(':id_veiculo', $id_veiculo, PDO::PARAM_INT);
            $stmt_nome_veiculo->execute();
            $veiculo_info = $stmt_nome_veiculo->fetch(PDO::FETCH_ASSOC);

            if ($veiculo_info) {
                $nome_veiculo_excluido = htmlspecialchars($veiculo_info['marca'] . ' ' . $veiculo_info['modelo']);
            }

            $conexao->beginTransaction();

            // 1. Buscar os caminhos das imagens associadas ao veículo
            $stmt_img_select = $conexao->prepare("SELECT id_imagem, caminho FROM tb_imagem_veiculo WHERE id_veiculo = :id_veiculo");
            $stmt_img_select->bindParam(':id_veiculo', $id_veiculo, PDO::PARAM_INT);
            $stmt_img_select->execute();
            $imagens_para_deletar = $stmt_img_select->fetchAll(PDO::FETCH_ASSOC);

            // 2. Deletar registros da tabela tb_imagem_veiculo
            $stmt_img_delete = $conexao->prepare("DELETE FROM tb_imagem_veiculo WHERE id_veiculo = :id_veiculo");
            $stmt_img_delete->bindParam(':id_veiculo', $id_veiculo, PDO::PARAM_INT);
            $stmt_img_delete->execute();

            // 3. Deletar o registro da tabela tb_veiculo
            $stmt_veiculo_delete = $conexao->prepare("DELETE FROM tb_veiculo WHERE id_veiculo = :id_veiculo");
            $stmt_veiculo_delete->bindParam(':id_veiculo', $id_veiculo, PDO::PARAM_INT);
            $stmt_veiculo_delete->execute();

            if ($stmt_veiculo_delete->rowCount() > 0) {
                // 4. Deletar os arquivos de imagem do servidor
                foreach ($imagens_para_deletar as $imagem) {
                    if (!empty($imagem['caminho'])) {
                        $caminho_completo_imagem = $uploaddir . $imagem['caminho'];
                        if (file_exists($caminho_completo_imagem)) {
                            unlink($caminho_completo_imagem);
                        }
                    }
                }
                $conexao->commit();
                $_SESSION['sucesso'] = "{$nome_veiculo_excluido} e suas imagens foram excluídos com sucesso!";
            } else {
                $conexao->rollBack();
                // Esta mensagem ocorrerá se o ID do veículo for válido mas não encontrado no momento da exclusão (ex: já excluído em outra aba)
                $_SESSION['erro'] = "Erro: {$nome_veiculo_excluido} não encontrado para exclusão ou já havia sido excluído.";
            }

        } catch (PDOException $e) {
            $conexao->rollBack();
            error_log("Erro na exclusão do veículo ID {$id_veiculo}: " . $e->getMessage());
            $_SESSION['erro'] = "Erro ao processar a exclusão de {$nome_veiculo_excluido}. Detalhe: " . $e->getMessage();
        }
    } else {
        // Esta mensagem é para o caso de id_veiculo não ser enviado corretamente via POST
        $_SESSION['erro'] = "ID do veículo inválido ou não fornecido para exclusão.";
    }
} else {
    $_SESSION['erro'] = "Método de requisição inválido para exclusão.";
}

header('Location: ../consulta.php');
exit();
?>