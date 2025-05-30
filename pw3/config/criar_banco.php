<?php
$host = 'localhost';
$user = 'root';
$pass = '';

try {
    // Conectar ao MySQL sem selecionar banco
    $pdo = new PDO("mysql:host=$host", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Apagar banco se existir
    $pdo->exec("DROP DATABASE IF EXISTS db_agenda");
    echo "Banco de dados anterior removido com sucesso!<br>";

    // Criar novo banco
    $pdo->exec("CREATE DATABASE db_agenda");
    echo "Novo banco de dados criado com sucesso!<br>";

    // Selecionar o banco
    $pdo->exec("USE db_agenda");

    // Criar tabela imoveis
    $pdo->exec("CREATE TABLE imoveis (
        id INT PRIMARY KEY AUTO_INCREMENT,
        controle VARCHAR(10),
        nome VARCHAR(100),
        endereco VARCHAR(200),
        telefone VARCHAR(15),
        email VARCHAR(100),
        valor DECIMAL(10,2),
        lote VARCHAR(50),
        tipo VARCHAR(50),
        tipo_contrato CHAR(1),
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )");
    echo "Tabela imoveis criada com sucesso!<br>";

    // Criar tabela imagens
    $pdo->exec("CREATE TABLE imagens (
        id INT PRIMARY KEY AUTO_INCREMENT,
        imovel_id INT,
        nome_arquivo VARCHAR(255),
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (imovel_id) REFERENCES imoveis(id) ON DELETE CASCADE
    )");
    echo "Tabela imagens criada com sucesso!<br>";

    echo "<br>Processo concluído com sucesso!";

} catch (PDOException $e) {
    die("Erro: " . $e->getMessage());
}
?>