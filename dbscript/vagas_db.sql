-- 1. Criação do Banco de Dados
CREATE DATABASE IF NOT EXISTS vagas_db;
USE vagas_db;

-- 2. Tabela de Usuários (Administradores e Comuns)
CREATE TABLE usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    senha VARCHAR(255) NOT NULL, -- Armazenará o HASH da senha
    foto VARCHAR(255) DEFAULT 'default.png', -- Caminho da imagem
    linkedin VARCHAR(255),
    tipo ENUM('admin', 'user') DEFAULT 'user', -- Define o nível de acesso
    data_cadastro DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- 3. Tabela de Categorias das Vagas
CREATE TABLE categorias (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(50) NOT NULL
);

-- 4. Tabela de Vagas
CREATE TABLE vagas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    titulo VARCHAR(100) NOT NULL,
    descricao TEXT NOT NULL,
    salario DECIMAL(10, 2), -- Ex: 2500.00
    imagem VARCHAR(255), -- Caminho da imagem ilustrativa da vaga
    ativo TINYINT(1) DEFAULT 1, -- 1 = Ativa (Visível), 0 = Inativa (Oculta)
    categoria_id INT NOT NULL,
    data_criacao DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (categoria_id) REFERENCES categorias(id)
);

-- 5. Tabela de Candidaturas (Relacionamento N:N entre Usuário e Vaga)
CREATE TABLE candidaturas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario_id INT NOT NULL,
    vaga_id INT NOT NULL,
    data_candidatura DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id),
    FOREIGN KEY (vaga_id) REFERENCES vagas(id),
    UNIQUE(usuario_id, vaga_id) -- Impede que o usuário se candidate 2x na mesma vaga
);

-- ======================================================
-- DADOS INICIAIS (SEED)
-- ======================================================

-- Inserindo categorias padrão
INSERT INTO categorias (nome) VALUES 
('Desenvolvimento'), 
('Design'), 
('Marketing'), 
('Recursos Humanos');

-- Inserindo um ADMINISTRADOR Padrão
-- ATENÇÃO: A senha abaixo é 'admin'. O hash foi gerado via PHP password_hash()
INSERT INTO usuarios (nome, email, senha, tipo) VALUES 
('Administrador', 'admin@sistema.com', '$2y$10$enC0YMZQ337QJw5ohsZ83.3n2MQBZkrf/B9V.4ost7XiYcUNfSgyq', 'admin');