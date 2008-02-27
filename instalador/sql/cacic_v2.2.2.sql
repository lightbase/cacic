-- script para converter o banco do cacic-2.2.2 para a nova versão.

-- script para acrescentar a coluna te_locais_secundarios ah tabela usuarios
-- em funcao de implementacao do conceito *locais secundarios* na versao 2.2.3-dev

ALTER TABLE usuarios ADD te_locais_secundarios varchar(200) DEFAULT NULL;

-- Acrescentar a coluna te_so ah tabela so
-- para futura implementacao de classificacao dinamica de versoes do Sistema Operacional

ALTER TABLE so ADD te_so varchar(50) DEFAULT NULL;

-- Acrescentar a coluna id_ftp ah tabela redes_grupos_ftp
-- para corrigir liberacao de sessao iniciada a partir do cliente (Gerente de Coletas) quando em operacao de  FTP.

ALTER TABLE redes_grupos_ftp ADD id_ftp int(11) NOT NULL auto_increment;
ALTER TABLE redes_grupos_ftp ADD PRIMARY KEY ( `id_ftp` ); 

-- Acrescentar a coluna te_exibe_graficos as tabelas configuracoes_padrao e configuracoes_locais 
-- para indicativo de exibicao dos graficos pizza da pagina principal

ALTER TABLE configuracoes_padrao ADD te_exibe_graficos varchar(100) NOT NULL DEFAULT "[acessos_locais][so][acessos][locais]";
ALTER TABLE configuracoes_locais ADD te_exibe_graficos varchar(100) NOT NULL DEFAULT "[acessos_locais][so][acessos][locais]";

-- Acrescentar a coluna id_local aa tabela acoes_excecoes, para aplicacao por local
ALTER TABLE acoes_excecoes ADD id_local int(11) NOT NULL DEFAULT 0;

-- Alterar a coluna cs_notificacao_ativada aa tabela descricao_hardware, para aplicacao por local
ALTER TABLE descricao_hardware CHANGE cs_notificacao_ativada te_locais_notificacao_ativada text;
