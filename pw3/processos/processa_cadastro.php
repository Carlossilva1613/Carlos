<?php
ob_start();
session_start();
require_once __DIR__ . '/../processos/conexao.php';

// Função para sanitizar inputs com verificação de null
function sanitize_input($data)
{
    if ($data === null) {
        return '';
    }
    return htmlspecialchars(stripslashes(trim($data)));
}

try {
    // Verifica se é POST
    if ($_SERVER["REQUEST_METHOD"] != "POST") {
        throw new Exception("Método inválido");
    }

    // Array de campos obrigatórios
    $campos_obrigatorios = [
        'marca' => 'Marca',
        'modelo' => 'Modelo',
        'ano' => 'Ano',
        'placa' => 'Placa',
        'cor' => 'Cor',
        'valor' => 'Valor' // No banco será 'preco'
    ];

    // Verifica campos vazios
    $campos_faltantes = [];
    foreach ($campos_obrigatorios as $campo => $nome) {
        if (empty($_POST[$campo])) {
            $campos_faltantes[] = $nome;
        }
    }

    if (!empty($campos_faltantes)) {
        $_SESSION['erro'] = "Campos obrigatórios não preenchidos: " . implode(', ', $campos_faltantes);
        header("Location: ../cadastro.php");
        exit();
    }

    // Receber e sanitizar dados do formulário com verificação de existência
    $marca = sanitize_input($_POST['marca'] ?? '');
    $modelo = sanitize_input($_POST['modelo'] ?? '');
    $ano = sanitize_input($_POST['ano'] ?? '');
    $placa = sanitize_input($_POST['placa'] ?? '');
    $cor = sanitize_input($_POST['cor'] ?? '');
    $preco = str_replace(['R$', '.', ','], ['', '', '.'], $_POST['valor'] ?? ''); // Usar $preco para o banco

    // TODO: Obter o id_usuario da sessão do usuário logado ou de outra fonte.
    // Por enquanto, usando um valor fixo como exemplo.
    $id_usuario = 1;

    // Verificar/criar pasta uploads
    $uploaddir = __DIR__ . '/../uploads/';
    if (!file_exists($uploaddir)) {
        mkdir($uploaddir, 0777, true);
    }

    $conexao->beginTransaction();

    // Preparar e executar a query para tb_veiculo
    // Colunas: id_usuario, marca, modelo, ano, placa, cor, preco
    // id_veiculo é autoincremento. titulo, descricao, criado_em são omitidos (assumindo NULL/DEFAULT)
    $stmt = $conexao->prepare("
        INSERT INTO tb_veiculo (id_usuario, marca, modelo, ano, placa, cor, preco) 
        VALUES (?, ?, ?, ?, ?, ?, ?)
    ");

    $stmt->execute([
        $id_usuario,
        $marca,
        $modelo,
        $ano,
        $placa,
        $cor,
        $preco
    ]);

    // Pegar o ID do veículo recém inserido (id_veiculo)
    $id_veiculo = $conexao->lastInsertId();

    // Upload de imagens
    $imagens_nomes_arquivos = [];
    if (isset($_FILES['imagens'])) {
        // $uploaddir já definido acima e verificado

        foreach ($_FILES['imagens']['tmp_name'] as $key => $tmp_name) {
            if ($_FILES['imagens']['error'][$key] === UPLOAD_ERR_OK) {
                $filename = uniqid() . '_' . basename($_FILES['imagens']['name'][$key]);
                $uploadfile = $uploaddir . $filename;

                if (move_uploaded_file($tmp_name, $uploadfile)) {
                    $imagens_nomes_arquivos[] = $filename;
                    // Inserir na tabela tb_imagem_veiculo
                    // Colunas: id_veiculo, caminho
                    $sql_img = "INSERT INTO tb_imagem_veiculo (id_veiculo, caminho) VALUES (:id_veiculo, :caminho)";
                    $stmt_img = $conexao->prepare($sql_img);
                    $stmt_img->execute([
                        ':id_veiculo' => $id_veiculo,
                        ':caminho' => $filename // Usar a coluna 'caminho'
                    ]);
                }
            }
        }
    }

    $conexao->commit();

    $_SESSION['cadastro_sucesso'] = [
        'marca' => $marca,
        'modelo' => $modelo,
        'ano' => $ano,
        'placa' => $placa,
        'cor' => $cor,
        'valor' => $preco, // Armazenar o valor processado (preco)
        'imagens' => $imagens_nomes_arquivos
    ];

    header('Location: ../cadastro.php?sucesso=1');
    exit();

} catch (PDOException $e) {
    $conexao->rollBack();
    $_SESSION['erro'] = "Erro ao cadastrar (PDO): " . $e->getMessage(); // Adicionar mais detalhes do erro PDO
    header('Location: ../cadastro.php?erro=1');
    exit();
} catch (Exception $e) {
    $_SESSION['erro'] = "Erro: " . $e->getMessage(); // Adicionar mais detalhes do erro genérico
    ob_clean();
    header("Location: ../cadastro.php?erro=1"); // Adicionar ?erro=1 para consistência
    exit();
}
?>