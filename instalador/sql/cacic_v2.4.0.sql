-- --------------------------------------------------------
-- Atualização de Tabelas do banco de dados CACIC-2.4.0
-- SGBD: MySQL-4.1.20
-- --------------------------------------------------------

SET FOREIGN_KEY_CHECKS = 0;

ALTER TABLE configuracoes_padrao
    ADD nu_resolucao_grafico_h smallint unsigned default 320,
    ADD nu_resolucao_grafico_w smallint unsigned default 240;

SET FOREIGN_KEY_CHECKS = 1;
