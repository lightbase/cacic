-- ----------------------------------------------------------
-- Atualização de Tabelas do banco de dados CACIC-v2.4.0-rc1
-- SGBD: MySQL-4.1.20
-- ----------------------------------------------------------

ALTER TABLE aquisicoes
    DROP PRIMARY KEY,
    MODIFY id_aquisicao int(10) auto_increment,
    MODIFY nr_notafiscal varchar(20),
    ADD PRIMARY KEY (id_aquisicao),
    ENGINE=InnoDB CHARACTER SET=latin1;

UPDATE descricoes_colunas_computadores 
    SET nm_campo='te_cpu_frequencia' 
    WHERE nm_campo='te_cpu_freq';

