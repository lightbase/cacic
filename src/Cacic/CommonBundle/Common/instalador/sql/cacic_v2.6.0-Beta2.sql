-- Atualização de Tabelas do banco de dados CACIC-v2.6.0-Beta2

SET FOREIGN_KEY_CHECKS = 0;

alter table configuracoes_locais add te_notificar_utilizacao_usb text ; 
alter table usuarios change te_locais_secundarios te_locais_secundarios text ;

SET FOREIGN_KEY_CHECKS = 1;

