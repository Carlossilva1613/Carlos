-- 1) Criar o banco de dados
CREATE DATABASE IF NOT EXISTS veiculos
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;
USE veiculos;

-- 2) Tabela de usuários
CREATE TABLE IF NOT EXISTS tb_usuario (
  id_usuario   INT AUTO_INCREMENT PRIMARY KEY,
  nome         VARCHAR(100)  NOT NULL,
  email        VARCHAR(150)  NOT NULL UNIQUE,
  senha        VARCHAR(255)  NOT NULL,
  criado_em    DATETIME      NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB
  DEFAULT CHARSET=utf8mb4
  COLLATE=utf8mb4_unicode_ci;

-- 3) Tabela de veículos (sem o campo controle)
CREATE TABLE IF NOT EXISTS tb_veiculo (
  id_veiculo     INT AUTO_INCREMENT PRIMARY KEY,
  id_usuario     INT           NOT NULL,
  marca          VARCHAR(100)         NOT NULL,
  modelo         VARCHAR(100)         NOT NULL,
  ano            YEAR                 NOT NULL,
  placa          VARCHAR(10)          NOT NULL,
  cor            VARCHAR(50)          NOT NULL,
  titulo         VARCHAR(200)         NOT NULL,
  descricao      TEXT                       NULL,
  preco          DECIMAL(10,2)     NOT NULL DEFAULT 0.00,
  criado_em      DATETIME           NOT NULL DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (id_usuario)
  REFERENCES tb_usuario(id_usuario)
  ON DELETE CASCADE
) ENGINE=InnoDB
  DEFAULT CHARSET=utf8mb4
  COLLATE=utf8mb4_unicode_ci;

-- 4) Tabela de imagens de veículos
CREATE TABLE IF NOT EXISTS tb_imagem_veiculo (
  id_imagem    INT AUTO_INCREMENT PRIMARY KEY,
  id_veiculo   INT           NOT NULL,
  caminho      VARCHAR(255)  NOT NULL,
  criado_em    DATETIME      NOT NULL DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (id_veiculo)
  REFERENCES tb_veiculo(id_veiculo)
  ON DELETE CASCADE
) ENGINE=InnoDB
  DEFAULT CHARSET=utf8mb4
  COLLATE=utf8mb4_unicode_ci;

