create database banco_bdex;
use banco_bdex;

CREATE TABLE Empresa (
    id_empresa INT PRIMARY KEY AUTO_INCREMENT NOT NULL,
    nome_empresa VARCHAR(255) NOT NULL,
    cnpj VARCHAR(18) NOT NULL,
    endereco VARCHAR(255) NOT NULL,
    email_empresa VARCHAR(255) NOT NULL,
    telefone_empresa VARCHAR(20) NOT NULL,
    senha_empresa VARCHAR(255) NOT NULL,
    status_empresa boolean default true
);

CREATE TABLE Desenvolvedor (
    id_desenvolvedor INT PRIMARY KEY AUTO_INCREMENT NOT NULL,
    nome_desenvolvedor VARCHAR(255) NOT NULL,
    telefone_desenvolvedor VARCHAR(20) NOT NULL,
    email_desenvolvedor VARCHAR(255) NOT NULL,
    endereco_desenvolvedor VARCHAR(255) NOT NULL,
    cpf VARCHAR(14) NOT NULL,
	skills TEXT NOT NULL, -- lista pode ser tratada como JSON ou texto separado por vírgula
    senha_desenvolvedor VARCHAR(255) NOT NULL,
	status_desenvolvedor boolean default true
);

CREATE TABLE Vaga (
    id_vaga INT PRIMARY KEY AUTO_INCREMENT NOT NULL,
    id_empresa INT NOT NULL,
    titulo_vaga VARCHAR(255) NOT NULL,
    descricao_vaga TEXT NOT NULL,
    data_publicacao DATE NOT NULL,
    valor_oferta FLOAT,
    FOREIGN KEY (id_empresa) REFERENCES Empresa(id_empresa)
);

CREATE TABLE Solicitacao (
    id_solicitacao INT AUTO_INCREMENT PRIMARY KEY NOT NULL,
    id_desenvolvedor INT NOT NULL,
    id_vaga INT NOT NULL,
    FOREIGN KEY (id_desenvolvedor) REFERENCES Desenvolvedor(id_desenvolvedor),
    FOREIGN KEY (id_vaga) REFERENCES Vaga(id_vaga)
);

CREATE TABLE Conexao (
    id_conexao INT PRIMARY KEY AUTO_INCREMENT NOT NULL,
    id_empresa INT NOT NULL,
    id_desenvolvedor INT NOT NULL,
    id_vaga INT NOT NULL,
    data_conexao DATETIME NOT NULL,
    FOREIGN KEY (id_empresa) REFERENCES Empresa(id_empresa),
    FOREIGN KEY (id_desenvolvedor) REFERENCES Desenvolvedor(id_desenvolvedor),
    FOREIGN KEY (id_vaga) REFERENCES Vaga(id_vaga)
);


-- Para visualizar os dados inseridos
select * from Empresa;
select * from Desenvolvedor;
select * from Vaga;
select * from Solicitacao;
select * from Conexao;