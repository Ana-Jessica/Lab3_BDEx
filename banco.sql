-- Cria o banco de dados
CREATE DATABASE IF NOT EXISTS banco_bdex;

-- Usa o banco de dados
USE banco_bdex;

-- Tabela para empresas
CREATE TABLE IF NOT EXISTS empresa (
    id_empresa INT PRIMARY KEY AUTO_INCREMENT NOT NULL,
    nome_empresa VARCHAR(255) NOT NULL,
    cnpj_empresa VARCHAR(18) NOT NULL UNIQUE, -- CNPJ deve ser único
    endereco_empresa VARCHAR(255) NOT NULL,
    email_empresa VARCHAR(255) NOT NULL UNIQUE, -- Email deve ser único
    telefone_empresa VARCHAR(20) NOT NULL,
    senha_empresa VARCHAR(255) NOT NULL,
    ativo BOOLEAN DEFAULT TRUE
);

-- Tabela para desenvolvedores
CREATE TABLE IF NOT EXISTS desenvolvedor (
    id_desenvolvedor INT PRIMARY KEY AUTO_INCREMENT NOT NULL,
    nome_desenvolvedor VARCHAR(255) NOT NULL,
    telefone_desenvolvedor VARCHAR(20) NOT NULL,
    email_desenvolvedor VARCHAR(255) NOT NULL UNIQUE, -- Email deve ser único
    endereco_desenvolvedor VARCHAR(255) NOT NULL,
    cpf_desenvolvedor VARCHAR(14) NOT NULL UNIQUE, -- CPF deve ser único
    skills_desenvolvedor TEXT NOT NULL, -- Pode ser uma string JSON ou texto separado por vírgula
    senha_desenvolvedor VARCHAR(255) NOT NULL,
    ativo BOOLEAN DEFAULT TRUE
);

-- Tabela para vagas
CREATE TABLE IF NOT EXISTS vaga (
    id_vaga INT PRIMARY KEY AUTO_INCREMENT NOT NULL,
    id_empresa INT NOT NULL,
    titulo_vaga VARCHAR(255) NOT NULL,
    descricao_vaga TEXT NOT NULL,
    data_publicacao DATE NOT NULL,
    valor_oferta FLOAT,
    status_vaga VARCHAR(50) DEFAULT 'ativa', -- 'ativa', 'fechada', 'conectada'
    FOREIGN KEY (id_empresa) REFERENCES empresa(id_empresa) ON DELETE CASCADE
);

-- Tabela para solicitações (candidaturas)
CREATE TABLE IF NOT EXISTS solicitacao (
    id_solicitacao INT AUTO_INCREMENT PRIMARY KEY NOT NULL,
    id_desenvolvedor INT NOT NULL,
    id_vaga INT NOT NULL,
    data_solicitacao DATETIME DEFAULT CURRENT_TIMESTAMP,
    status_solicitacao VARCHAR(50) DEFAULT 'pendente', -- 'pendente', 'aceita', 'rejeitada'
    FOREIGN KEY (id_desenvolvedor) REFERENCES desenvolvedor(id_desenvolvedor) ON DELETE CASCADE,
    FOREIGN KEY (id_vaga) REFERENCES vaga(id_vaga) ON DELETE CASCADE,
    UNIQUE (id_desenvolvedor, id_vaga) -- Um desenvolvedor só pode se candidatar uma vez por vaga
);

-- Tabela para conexões (vagas finalizadas com desenvolvedor selecionado)
CREATE TABLE IF NOT EXISTS conexao (
    id_conexao INT PRIMARY KEY AUTO_INCREMENT NOT NULL,
    id_empresa INT NOT NULL,
    id_desenvolvedor INT NOT NULL,
    id_vaga INT NOT NULL,
    data_conexao DATETIME DEFAULT CURRENT_TIMESTAMP,
    status_conexao ENUM('aceita', 'encerrada', 'concluida') DEFAULT 'aceita',
    justificativa TEXT NULL,
    FOREIGN KEY (id_empresa) REFERENCES empresa(id_empresa) ON DELETE CASCADE,
    FOREIGN KEY (id_desenvolvedor) REFERENCES desenvolvedor(id_desenvolvedor) ON DELETE CASCADE,
    FOREIGN KEY (id_vaga) REFERENCES vaga(id_vaga) ON DELETE CASCADE
);

-- Tabela para tokens de redefinição de senha
CREATE TABLE IF NOT EXISTS tokens_reset_senha (
    id_tokenEmail INT PRIMARY KEY AUTO_INCREMENT,
    email_envio VARCHAR(255) NOT NULL,
    token_email VARCHAR(255) NOT NULL,
    created_at DATETIME NOT NULL,
    expires_at DATETIME NOT NULL,
    INDEX idx_token (token),
    INDEX idx_email (email)
);


CREATE TABLE IF NOT EXISTS historico_usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_empresa INT DEFAULT NULL,
    id_desenvolvedor INT DEFAULT NULL,
    tipo_usuario ENUM('empresa', 'desenvolvedor') NOT NULL,
    acao ENUM('desativacao', 'reativacao') NOT NULL,
    motivo TEXT,
    data_hora TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    ip VARCHAR(45),
    FOREIGN KEY (id_empresa) REFERENCES empresa(id_empresa) ON DELETE SET NULL,
    FOREIGN KEY (id_desenvolvedor) REFERENCES desenvolvedor(id_desenvolvedor) ON DELETE SET NULL
);

-- Para visualizar os dados inseridos
select * from empresa;
select * from desenvolvedor;
select * from vaga;
select * from solicitacao;
select * from conexao;

