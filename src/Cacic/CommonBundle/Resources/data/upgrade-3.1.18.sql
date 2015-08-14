CREATE OR REPLACE FUNCTION upgrade_3118() RETURNS VOID AS $$
DECLARE
  prop record;
  soft record;
  v_id_relatorio integer;
  v_id_usuario integer;
  v_nome VARCHAR;
BEGIN
  -- Cria modelo de dados esperado
  RAISE NOTICE 'Alterando modelo de dados...'
  ALTER TABLE software_estacao
  DROP CONSTRAINT fk_9bbdd0f82aff7683cf537cbe;

  DROP INDEX idx_9bbdd0f82aff7683cf537cbe;

  ALTER TABLE software_estacao
  ADD id_aquisicao_item INT DEFAULT NULL;

  ALTER TABLE software_estacao
  DROP id_tipo_licenca;

  ALTER TABLE software_estacao
  DROP id_aquisicao;

  ALTER TABLE software_estacao
  ADD CONSTRAINT FK_9BBDD0F8EEB8A73C
  FOREIGN KEY (id_aquisicao_item)
  REFERENCES aquisicao_item (id_aquisicao_item) NOT DEFERRABLE INITIALLY IMMEDIATE;

  CREATE INDEX IDX_9BBDD0F8EEB8A73C
  ON software_estacao (id_aquisicao_item);

  DROP INDEX aquisicao_item_pkey;

  ALTER TABLE aquisicao_item
  ADD id_aquisicao_item SERIAL;

  ALTER TABLE aquisicao_item
  ADD id_software_relatorio INT DEFAULT NULL;

  ALTER TABLE aquisicao_item
  ALTER id_tipo_licenca DROP NOT NULL;

  ALTER TABLE aquisicao_item
  ALTER id_aquisicao DROP NOT NULL;

  ALTER TABLE aquisicao_item
  ADD CONSTRAINT FK_D3DE1A3AF1DAE794
  FOREIGN KEY (id_software_relatorio)
  REFERENCES software_relatorio (id_relatorio) NOT DEFERRABLE INITIALLY IMMEDIATE;

  RAISE NOTICE 'Modelo de dados alterado com sucesso! Movendo relatórios...';

  -- Move relatórios cadastrados no controle de licenças para os relatórios
  FOR soft IN select *
              from aquisicoes_software aq
                INNER JOIN aquisicao_item ai
                  on (aq.id_tipo_licenca = ai.id_tipo_licenca
                      and aq.id_aquisicao = ai.id_aquisicao)
                INNER JOIN aquisicao a
                  on a.id_aquisicao = aq.id_aquisicao
  LOOP
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


         INSERT INTO
           software_relatorio (id_usuario,
                               nome_relatorio,
                               nivel_acesso,
                               habilita_notificacao
           )
         VALUES (v_id_usuario,
                 soft.nr_processo,
                 'publico',
                 FALSE
         );

    END IF;

  END LOOP ;


  -- Adiciona restrições se tudo correu bem
  CREATE INDEX IDX_D3DE1A3AF1DAE794
  ON aquisicao_item (id_software_relatorio);

  ALTER TABLE aquisicao_item
  ADD PRIMARY KEY (id_aquisicao_item);


  RETURN;
END;
$$ LANGUAGE 'plpgsql';