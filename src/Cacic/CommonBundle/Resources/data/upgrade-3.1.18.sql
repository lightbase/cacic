CREATE OR REPLACE FUNCTION upgrade_3118() RETURNS VOID AS $$
DECLARE
  prop record;
  soft record;
  v_id_relatorio integer;
  v_id_usuario integer;
  v_id_aquisicao_item integer;
  v_nome VARCHAR;
BEGIN
  -- Cria modelo de dados esperado
  RAISE NOTICE 'Alterando modelo de dados...';

  ALTER TABLE aquisicoes_software
  DROP CONSTRAINT aquisicoes_software_pkey;

  ALTER TABLE aquisicoes_software
  DROP CONSTRAINT fk_6bcde8b1270b845a;

  ALTER TABLE aquisicoes_software
  DROP CONSTRAINT fk_6bcde8b1cf537cbe2aff7683;

  ALTER TABLE aquisicoes_software
  ADD id_relatorio INT;

  ALTER TABLE aquisicoes_software
  ADD id_aquisicao_item INT;

  ALTER TABLE aquisicoes_software
  ALTER COLUMN id_software
  DROP NOT NULL;

  ALTER TABLE aquisicoes_software
  ALTER COLUMN id_tipo_licenca
  DROP NOT NULL;

  ALTER TABLE aquisicoes_software
  ALTER COLUMN id_aquisicao
  DROP NOT NULL;

  --ALTER TABLE software_estacao
  --DROP CONSTRAINT fk_9bbdd0f82aff7683cf537cbe;

  --DROP INDEX idx_9bbdd0f82aff7683cf537cbe;

  --ALTER TABLE software_estacao
  --ADD id_aquisicao_item INT;

  ALTER TABLE aquisicao_item
  DROP CONSTRAINT aquisicao_item_pkey;

  ALTER TABLE aquisicao_item
  ADD id_aquisicao_item INTEGER;

  CREATE SEQUENCE aquisicao_item_id_aquisicao_item_seq;

  ALTER TABLE aquisicao_item
  ALTER COLUMN id_aquisicao_item
  SET DEFAULT nextval('aquisicao_item_id_aquisicao_item_seq');

  ALTER TABLE aquisicao_item
  ALTER id_tipo_licenca DROP NOT NULL;

  ALTER TABLE aquisicao_item
  ALTER id_aquisicao DROP NOT NULL;

  -- Inserção do valor para a nova chave primária
  CREATE TABLE aquisicao_item_bk AS
    SELECT * FROM aquisicao_item;

  DELETE FROM aquisicao_item;

  FOR soft IN select *
              from aquisicao_item_bk
  LOOP

    v_id_aquisicao_item = nextval('aquisicao_item_id_aquisicao_item_seq');

    RAISE NOTICE 'Criando chave primária para id_tipo_licenca = % e id_aquisicao = % | id_aquisicao_item = %', soft.id_tipo_licenca, soft.id_aquisicao, v_id_aquisicao_item;

    INSERT INTO aquisicao_item (
      id_aquisicao_item,
      id_tipo_licenca,
      id_aquisicao,
      qt_licenca,
      dt_vencimento_licenca,
      te_obs
    ) VALUES (
      v_id_aquisicao_item,
      soft.id_tipo_licenca,
      soft.id_aquisicao,
      soft.qt_licenca,
      soft.dt_vencimento_licenca,
      soft.te_obs
    );

  END LOOP;

  -- Tem que garantir unicidade da chave
  DELETE FROM aquisicao_item
  WHERE id_aquisicao_item IS NULL;

  --ALTER TABLE aquisicao_item
  --ADD PRIMARY KEY (id_aquisicao_item);

  RAISE NOTICE 'Criando relatórios cadastrados';

  -- Move relatórios cadastrados no controle de licenças para os relatórios
  FOR soft IN select *
              from aquisicoes_software aq
                INNER JOIN aquisicao_item ai
                  on (aq.id_tipo_licenca = ai.id_tipo_licenca
                      and aq.id_aquisicao = ai.id_aquisicao)
                INNER JOIN aquisicao a
                  on a.id_aquisicao = aq.id_aquisicao
  LOOP
    SELECT id_aquisicao_item INTO v_id_aquisicao_item
    from aquisicao_item
    WHERE id_tipo_licenca = soft.id_tipo_licenca
          and id_aquisicao = soft.id_aquisicao;

    RAISE NOTICE 'Movendo software id_software = %, id_tipo_licenca = %, id_aquisicao = %, id_aquisicao_item = %',
    soft.id_software,
    soft.id_tipo_licenca,
    soft.id_aquisicao,
    v_id_aquisicao_item;

    -- Crio um relatório se ainda não existir
    SELECT id_relatorio INTO v_id_relatorio
    FROM software_relatorio
    WHERE nome_relatorio = soft.nr_processo;

    IF v_id_relatorio is NULL
      THEN
        -- Busca primeiro usuário admin
        SELECT min(id_usuario) INTO v_id_usuario
        FROM usuario u
          INNER JOIN grupo_usuario g
            on u.id_grupo_usuario = g.id_grupo_usuario
        WHERE  g.role = 'ROLE_ADMIN' AND u.is_active = TRUE;

        v_id_relatorio = nextval('software_relatorio_id_relatorio_seq');

        RAISE NOTICE 'Criando relatório com id_relatorio = % e id_aquisicao_item = %',
        v_id_relatorio,
        v_id_aquisicao_item;

         INSERT INTO
           software_relatorio (
             id_relatorio,
             id_usuario,
             nome_relatorio,
             nivel_acesso,
             habilita_notificacao
           )
         VALUES (
           v_id_relatorio,
           v_id_usuario,
           soft.nr_processo,
           'publico',
           FALSE
         );

        INSERT INTO aquisicoes_software (
          id_relatorio,
          id_aquisicao_item
        ) VALUES (
          v_id_relatorio,
          v_id_aquisicao_item
        );

    END IF;

    INSERT INTO relatorios_software (
      id_software,
      id_relatorio
    ) VALUES (
      soft.id_software,
      v_id_relatorio
    );



  END LOOP ;

  RAISE NOTICE 'Modelo de dados alterado com sucesso! Movendo relatórios...';

  -- Adiciona restrições se tudo correu bem
  DELETE FROM aquisicoes_software
  WHERE id_aquisicao_item IS NULL;

  CREATE INDEX IDX_9BBDD0F8EEB8A73C
  ON software_estacao (id_aquisicao_item);

  --ALTER TABLE software_estacao
  --DROP id_tipo_licenca;

  --ALTER TABLE software_estacao
  --DROP id_aquisicao;

  ALTER TABLE aquisicao_item
  ADD PRIMARY KEY (id_aquisicao_item);

  ALTER TABLE software_estacao
  ADD CONSTRAINT FK_9BBDD0F8EEB8A73C
  FOREIGN KEY (id_aquisicao_item)
  REFERENCES aquisicao_item (id_aquisicao_item);

  ALTER TABLE aquisicoes_software
  DROP id_software;

  ALTER TABLE aquisicoes_software
  DROP id_aquisicao;

  ALTER TABLE aquisicoes_software
  DROP id_tipo_licenca;

  ALTER TABLE aquisicoes_software
  ADD CONSTRAINT FK_6BCDE8B1FE8CA628
  FOREIGN KEY (id_relatorio)
  REFERENCES software_relatorio (id_relatorio);

  ALTER TABLE aquisicoes_software
  ADD CONSTRAINT FK_6BCDE8B1F050FB48
  FOREIGN KEY (id_aquisicao_item)
  REFERENCES aquisicao_item (id_aquisicao_item);

  CREATE INDEX IDX_6BCDE8B1FE8CA628
  ON aquisicoes_software (id_relatorio);

  CREATE INDEX IDX_6BCDE8B1F050FB48
  ON aquisicoes_software (id_aquisicao_item);

  ALTER TABLE aquisicoes_software
  ADD PRIMARY KEY (id_relatorio, id_aquisicao_item);

  DROP TABLE aquisicao_item_bk;

  RAISE NOTICE 'Atualização terminada!';

  RETURN;
END;
$$ LANGUAGE 'plpgsql';