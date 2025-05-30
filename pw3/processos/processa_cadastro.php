<?php
ob_start();
session_start();

// corrige aqui: arquivo está na mesma pasta
require_once __DIR__ . '/conexao.php';

function sanitize_input($data) {
    return htmlspecialchars(stripslashes(trim($data)));
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // 1) campos obrigatórios
        $required = [
            'marca'        => 'Marca',
            'modelo'       => 'Modelo',
            'ano'          => 'Ano',
            'placa'        => 'Placa',
            'cor'          => 'Cor',
            'tipo_veiculo' => 'Tipo de Veículo',
            'titulo'       => 'Título',
            'preco'        => 'Preço'
        ];
        $missing = [];
        foreach ($required as $field => $label) {
            if (empty($_POST[$field])) {
                $missing[] = $label;
            }
        }
        if ($missing) {
            $_SESSION['erro'] = 'Preencha: ' . implode(', ', $missing);
            header('Location: cadastro.php');
            exit;
        }

        // 2) sanitiza
        $marca        = sanitize_input($_POST['marca']);
        $modelo       = sanitize_input($_POST['modelo']);
        $ano          = (int) $_POST['ano'];
        $placa        = sanitize_input($_POST['placa']);
        $cor          = sanitize_input($_POST['cor']);
        $tipo         = sanitize_input($_POST['tipo_veiculo']);
        $titulo       = sanitize_input($_POST['titulo']);
        $descricao    = sanitize_input($_POST['descricao'] ?? '');
        // formata "1.234,56" → "1234.56"
        $preco        = str_replace([',','.'], ['','.'], $_POST['preco']);

        // 3) gera controle sequencial
        $stmtMax = $conexao->query("SELECT MAX(controle) FROM tb_veiculo");
        $max     = $stmtMax->fetchColumn();
        $next    = $max !== null ? ((int)$max + 1) : 1;
        $controle = sprintf("%010d", $next);

        // 4) prepara upload
        $uploadDir = __DIR__ . '/uploads/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        $conexao->beginTransaction();

        // 5) insere veículo
        $sql = "INSERT INTO tb_veiculo 
                  (id_usuario, controle, marca, modelo, ano, placa, cor, tipo_veiculo, titulo, descricao, preco) 
                VALUES 
                  (?,  ?,        ?,     ?,     ?,   ?,     ?,   ?,            ?,      ?,         ?)";
        $stmt = $conexao->prepare($sql);
        // altera id_usuario conforme sessão, ou fixe em 1 se admin padrão
        $idUsuario = $_SESSION['user_id'] ?? 1;
        $stmt->execute([
            $idUsuario,
            $controle,
            $marca,
            $modelo,
            $ano,
            $placa,
            $cor,
            $tipo,
            $titulo,
            $descricao,
            $preco
        ]);
        $veiculo_id = $conexao->lastInsertId();

        // 6) upload de imagens
        if (!empty($_FILES['imagens']['tmp_name'])) {
            foreach ($_FILES['imagens']['tmp_name'] as $i => $tmp) {
                if ($_FILES['imagens']['error'][$i] === UPLOAD_ERR_OK) {
                    $name    = uniqid() . '_' . basename($_FILES['imagens']['name'][$i]);
                    $target  = $uploadDir . $name;
                    if (move_uploaded_file($tmp, $target)) {
                        $sqlImg = "INSERT INTO tb_imagem_veiculo (id_veiculo, caminho) VALUES (?, ?)";
                        $stmtImg = $conexao->prepare($sqlImg);
                        $stmtImg->execute([$veiculo_id, 'uploads/' . $name]);
                    }
                }
            }
        }

        $conexao->commit();

        // 7) prepara feedback
        $_SESSION['cadastro_sucesso'] = [
            'controle'     => $controle,
            'marca'        => $marca,
            'modelo'       => $modelo,
            'ano'          => $ano,
            'placa'        => $placa,
            'cor'          => $cor,
            'tipo_veiculo' => $tipo,
            'titulo'       => $titulo,
            'preco'        => number_format($preco, 2, ',', '.')
        ];
        header('Location: cadastro.php?sucesso=1');
        exit;

    } catch (Exception $e) {
        $conexao->rollBack();
        $_SESSION['erro'] = 'Erro: ' . $e->getMessage();
        header('Location: cadastro.php?erro=1');
        exit;
    }
}
?>
