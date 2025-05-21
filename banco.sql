create database banco_bdex;
use banco_bdex;

CREATE TABLE Empresa (
    id_empresa INT PRIMARY KEY auto_increment,
    nome_empresa VARCHAR(255),
    cnpj VARCHAR(18),
    endereco VARCHAR(255),
    email VARCHAR(255),
    telefone_empresa INT,
    senha_empresa VARCHAR(255)
);

CREATE TABLE Desenvolvedor (
    id_desenvolvedor INT PRIMARY KEY auto_increment,
    nome_desenvolvedor VARCHAR(255),
    telefone_desenvolvedor VARCHAR(20),
    email_desenvolvedor VARCHAR(255),
    cpf VARCHAR(14),
    linguagens_de_programacao TEXT, -- lista pode ser tratada como JSON ou texto separado por vírgula
    tecnologias TEXT,               -- mesma lógica da linha acima
    senha_desenvolvedor VARCHAR(255)
);

CREATE TABLE Vaga (
    id_vaga INT PRIMARY KEY auto_increment,
    id_empresa INT,
    titulo_vaga VARCHAR(255),
    descricao_vaga TEXT,
    valor_oferta FLOAT,
    FOREIGN KEY (id_empresa) REFERENCES Empresa(id_empresa)
);

CREATE TABLE Solicitacao (
    id_solicitacao INT PRIMARY KEY auto_increment,
    id_desenvolvedor INT,
    id_vaga INT,
    FOREIGN KEY (id_desenvolvedor) REFERENCES Desenvolvedor(id_desenvolvedor),
    FOREIGN KEY (id_vaga) REFERENCES Vaga(id_vaga)
);

CREATE TABLE Conexao (
    id_conexao INT PRIMARY KEY auto_increment,
    id_empresa INT,
    id_desenvolvedor INT,
    data_conexao DATETIME,
    FOREIGN KEY (id_empresa) REFERENCES Empresa(id_empresa),
    FOREIGN KEY (id_desenvolvedor) REFERENCES Desenvolvedor(id_desenvolvedor)
);
