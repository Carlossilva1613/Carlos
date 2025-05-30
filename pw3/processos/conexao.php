<?php
define('HOST', '127.0.0.1:3306');
define('USER', 'root');
define('PASS', '');
define('DBNAME', 'veiculos');

try {
    $conexao = new PDO('mysql:host=' . HOST . ';dbname=' . DBNAME, USER, PASS);
    $conexao->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $conexao->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    $conexao->exec('set names utf8mb4');

    // Criação da tabela de usuários
    $conexao->exec("CREATE TABLE IF NOT EXISTS usuarios (
        id INT AUTO_INCREMENT PRIMARY KEY,
        nome VARCHAR(255) NOT NULL,
        email VARCHAR(255) NOT NULL UNIQUE,
        senha VARCHAR(255) NOT NULL,
        criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )");
} catch (PDOException $e) {
    die('Erro na conexão com o banco de dados: ' . $e->getMessage());
}
?>