<?php
// processos/processa_atualizacao.php
require_once 'conexao.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        try {
                // Validar ID
                $id = filter_input(INPUT_POST, 'id', FILTER_SANITIZE_NUMBER_INT);
                if (!$id) {
                        throw new Exception("ID não fornecido ou inválido");
                }

                // Recebendo e sanitizando os dados
                $id = filter_input(INPUT_POST, 'id', FILTER_SANITIZE_NUMBER_INT);

                // Usando htmlspecialchars ao invés de FILTER_SANITIZE_STRING
                $nome = htmlspecialchars($_POST['Nome'] ?? '', ENT_QUOTES, 'UTF-8');
                $endereco = htmlspecialchars($_POST['endereco'] ?? '', ENT_QUOTES, 'UTF-8');
                $telefone = htmlspecialchars($_POST['telefone'] ?? '', ENT_QUOTES, 'UTF-8');
                $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);

                // Tratamento do valor monetário
                $valor = $_POST['valor'] ?? '';
                $valor = str_replace(['R$', ' '], '', $valor);
                $valor = str_replace('.', '', $valor); // Remove pontos de milhares
                $valor = str_replace(',', '.', $valor); // Converte vírgula em ponto
                $valor = (float) $valor; // Converte para float

                $controle = htmlspecialchars($_POST['controle'] ?? '', ENT_QUOTES, 'UTF-8');
                $lote = htmlspecialchars($_POST['lote'] ?? '', ENT_QUOTES, 'UTF-8');
                $tipo = htmlspecialchars($_POST['tipo'] ?? '', ENT_QUOTES, 'UTF-8');

                // Validações básicas
                if (empty($id) || empty($nome)) {
                        throw new Exception("ID e nome são obrigatórios");
                }

                // Preparando a query
                $sql = "UPDATE tb_veiculo SET 
                nome = :nome,
                endereco = :endereco,
                telefone = :telefone,
                email = :email,
                valor = :valor,
                controle = :controle,
                lote = :lote,
                tipo = :tipo
                WHERE id = :id";

                $stmt = $conexao->prepare($sql);

                // Executando a query com os parâmetros
                $resultado = $stmt->execute([
                        ':id' => $id,
                        ':nome' => $nome,
                        ':endereco' => $endereco,
                        ':telefone' => $telefone,
                        ':email' => $email,
                        ':valor' => $valor, // Agora o valor está como float
                        ':controle' => $controle,
                        ':lote' => $lote,
                        ':tipo' => $tipo
                ]);

                if ($resultado) {
                        echo "<script>
                    alert('Registro atualizado com sucesso!');
                    window.location.href='../index.php';
                  </script>";
                } else {
                        throw new Exception("Erro ao atualizar o registro");
                }

        } catch (Exception $e) {
                echo "<script>
                alert('Erro: " . htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8') . "');
                window.history.back();
              </script>";
        }
} else {
        header("Location: ../index.php");
        exit();
}
?>