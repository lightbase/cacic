-- ----------------------------------------------------------
-- Atualização de Tabelas do banco de dados CACIC-v2.4.0-rc2
-- SGBD: MySQL-4.1.20
-- ----------------------------------------------------------

ALTER TABLE componentes_estacoes_historico
    MODIFY te_valor text;
    