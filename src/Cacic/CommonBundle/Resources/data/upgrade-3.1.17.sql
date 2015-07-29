CREATE OR REPLACE FUNCTION upgrade_3117() RETURNS VOID AS $$
DECLARE
  prop record;
  v_data_coleta timestamp;
BEGIN

  -- Cria tabela de backup
  DROP TABLE IF EXISTS computador_coleta_historico_bak;
  CREATE TABLE computador_coleta_historico_bak AS
    SELECT * FROM computador_coleta_historico;

  ALTER TABLE computador_coleta_historico_bak
    ADD CONSTRAINT computador_coleta_historico_bak_pkey
    PRIMARY KEY (id_computador_coleta_historico);

  CREATE INDEX computador_coleta_historico_bak_value_idx ON
    computador_coleta_historico_bak (id_computador_coleta, id_computador, id_class_property, te_class_property_value);

  CREATE SEQUENCE computador_coleta_historico_bak_id_seq;

  ALTER TABLE computador_coleta_historico_bak
  ALTER COLUMN id_computador_coleta_historico
  SET DEFAULT nextval('computador_coleta_historico_bak_id_seq');

  -- Apaga todos os registros da tabela anterior
  DELETE FROM computador_coleta_historico;

  -- Cria índice para os campos se não existir
  RAISE NOTICE 'Criando índice...';

  IF NOT EXISTS (
      SELECT 1
      FROM   pg_class c
        JOIN   pg_namespace n ON n.oid = c.relnamespace
      WHERE  c.relname = 'computador_coleta_historico_value_idx'
  ) THEN
    create index computador_coleta_historico_value_idx
    on computador_coleta_historico (id_computador_coleta, id_computador, te_class_property_value);
  END IF;

  RAISE NOTICE 'Criação do índice concluída! Continuando...';

  -- Primeiro descobre os nomes de propriedades repetidos
  FOR prop IN select count(id_computador_coleta_historico) as n_coletas,
                id_computador,
                id_computador_coleta,
                id_class_property,
                te_class_property_value
              from computador_coleta_historico_bak
                WHERE dt_hr_inclusao is NOT NULL
              group by id_computador,
                id_computador_coleta,
                id_class_property,
                te_class_property_value
              ORDER BY count(id_computador_coleta_historico) DESC LOOP

    RAISE NOTICE '% coletas repetidas encontradas para id_computador_coleta = % e te_class_property_value = %',
      prop.n_coletas,
      prop.id_computador_coleta,
      prop.te_class_property_value;

    -- Utiliza somente o maior valor e apaga o resto
    SELECT max(dt_hr_inclusao) INTO v_data_coleta
    FROM computador_coleta_historico
    WHERE id_computador_coleta = prop.id_computador_coleta
          AND te_class_property_value = prop.te_class_property_value
          AND  id_computador = prop.id_computador;

    RAISE NOTICE 'Inserindo de volta id_computador_coleta = %', prop.id_computador_coleta;

    INSERT INTO computador_coleta_historico (
      id_computador_coleta,
      id_computador,
      id_class_property,
      te_class_property_value,
      dt_hr_inclusao
    ) VALUES (
      prop.id_computador_coleta,
      prop.id_computador,
      prop.id_class_property,
      prop.te_class_property_value,
      v_data_coleta
    );

  END LOOP;

  -- Agora apaga tabela temporária
  DROP TABLE computador_coleta_historico_bak;

  RETURN;
END;
$$ LANGUAGE 'plpgsql';
