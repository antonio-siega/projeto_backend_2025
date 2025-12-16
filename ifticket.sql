DROP DATABASE IF EXISTS eventos_if;
CREATE DATABASE eventos_if;
USE eventos_if;

CREATE TABLE organizacao (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(255) NOT NULL,
    contato VARCHAR(255),
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

CREATE TABLE local (
    id INT AUTO_INCREMENT PRIMARY KEY,
    endereco VARCHAR(255),
    capacidade_total INT,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

CREATE TABLE evento (
    id INT AUTO_INCREMENT PRIMARY KEY,
    organizacao_id INT,
    local_id INT,
    nome VARCHAR(255),
    descricao TEXT,
    status ENUM('rascunho', 'publicado', 'encerrado', 'cancelado'),
    politica_cancelamento TEXT,
    data_inicio DATETIME,
    data_fim DATETIME,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (organizacao_id) REFERENCES organizacao (id),
    FOREIGN KEY (local_id) REFERENCES local (id)
);

CREATE TABLE setor (
    id INT AUTO_INCREMENT PRIMARY KEY,
    evento_id INT,
    nome VARCHAR(255),
    capacidade INT,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (evento_id) REFERENCES evento (id)
);

CREATE TABLE lote (
    id INT AUTO_INCREMENT PRIMARY KEY,
    setor_id INT,
    preco DECIMAL(10,2),
    periodo_vigencia_ini DATETIME,
    periodo_vigencia_fim DATETIME,
    limite INT,
    status ENUM('ativo', 'inativo'),
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (setor_id) REFERENCES setor (id)
);

CREATE TABLE cliente (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(255),
    documento VARCHAR(30),
    contato VARCHAR(255),
    consentimento BOOLEAN,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

CREATE TABLE pedido (
    id INT AUTO_INCREMENT PRIMARY KEY,
    cliente_id INT,
    canal_venda ENUM('ecommerce', 'comissario', 'bilheteria'),
    comissario_id INT,
    setor_id INT,
    lote_id INT,
    quantidade INT,
    valor_bruto DECIMAL(10,2),
    taxa DECIMAL(10,2),
    desconto DECIMAL(10,2),
    total_liquido DECIMAL(10,2),
    status ENUM('pendente', 'aprovado', 'recusado', 'estornado', 'expirado'),
    prazo_expiracao DATETIME,
    data_criacao DATETIME DEFAULT CURRENT_TIMESTAMP,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (cliente_id) REFERENCES cliente (id),
    FOREIGN KEY (comissario_id) REFERENCES comissario (id),
    FOREIGN KEY (setor_id) REFERENCES setor (id),
    FOREIGN KEY (lote_id) REFERENCES lote (id)
);

CREATE TABLE pagamento (
    id INT AUTO_INCREMENT PRIMARY KEY,
    pedido_id INT,
    metodo ENUM('pix', 'cartao', 'boleto', 'dinheiro'),
    status ENUM('pendente', 'aprovado', 'recusado', 'estornado'),
    transacao_gateway VARCHAR(100),
    valor DECIMAL(10,2),
    taxa DECIMAL(10,2),
    data_aprovacao DATETIME,
    data_estorno DATETIME,
    motivo_estorno VARCHAR(255),
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (pedido_id) REFERENCES pedido (id)
);

CREATE TABLE ingresso (
    id INT AUTO_INCREMENT PRIMARY KEY,
    pedido_id INT,
    identificador_unico VARCHAR(36),
    qrcode VARCHAR(128),
    status ENUM('emitido', 'transferido', 'utilizado', 'cancelado'),
    titular_nome VARCHAR(255),
    titular_documento VARCHAR(30),
    data_emissao DATETIME,
    data_uso DATETIME,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (pedido_id) REFERENCES pedido (id)
);

CREATE TABLE checkin (
    id INT AUTO_INCREMENT PRIMARY KEY,
    ingresso_id INT,
    dispositivo_id INT,
    data_hora DATETIME,
    tentativa_duplicada BOOLEAN,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (ingresso_id) REFERENCES ingresso (id)
);

CREATE TABLE comissario (
    id INT AUTO_INCREMENT PRIMARY KEY,
    organizacao_id INT,
    nome VARCHAR(255),
    dados TEXT,
    ativo BOOLEAN,
    regra_comissao TEXT,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (organizacao_id) REFERENCES organizacao (id)
);

CREATE TABLE cupom (
    id INT AUTO_INCREMENT PRIMARY KEY,
    codigo VARCHAR(20),
    tipo ENUM('percentual', 'valor'),
    valor DECIMAL(10,2),
    periodo_ini DATETIME,
    periodo_fim DATETIME,
    limite_total INT,
    limite_cliente INT,
    canal_restrito ENUM('ecommerce', 'comissario', 'bilheteria'),
    comissario_id INT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (comissario_id) REFERENCES comissario (id)
);

CREATE TABLE comissao_pedido (
    id INT AUTO_INCREMENT PRIMARY KEY,
    pedido_id INT,
    comissario_id INT,
    valor_comissao DECIMAL(10,2),
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (pedido_id) REFERENCES pedido (id),
    FOREIGN KEY (comissario_id) REFERENCES comissario (id)
);

CREATE TABLE repasse_comissao (
    id INT AUTO_INCREMENT PRIMARY KEY,
    comissario_id INT,
    valor DECIMAL(10,2),
    periodo DATETIME,
    metodo ENUM('pix', 'dinheiro'),
    comprovante VARCHAR(100),
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (comissario_id) REFERENCES comissario (id)
);

CREATE TABLE usuario (
    id INT AUTO_INCREMENT PRIMARY KEY,
    organizacao_id INT,
    nome VARCHAR(255),
    perfil ENUM('organizador', 'bilheteria', 'financeiro', 'portaria', 'admin', 'cliente', 'comissario'),
    ativo BOOLEAN,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (organizacao_id) REFERENCES organizacao (id)
);

CREATE TABLE dispositivo_checkin (
    id INT AUTO_INCREMENT PRIMARY KEY,
    uuid CHAR(36),
    organizacao_id INT,
    sincronizado BOOLEAN,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (organizacao_id) REFERENCES organizacao (id)
);

CREATE TABLE auditoria (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario_id INT,
    acao VARCHAR(255),
    data_hora DATETIME,
    ip VARCHAR(45),
    entidade VARCHAR(50),
    campo VARCHAR(50),
    valor_antigo TEXT,
    valor_novo TEXT,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (usuario_id) REFERENCES usuario (id)
);


