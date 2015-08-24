CREATE OR REPLACE FUNCTION upgrade_3117() RETURNS VOID AS $$
DECLARE
  prop record;
  v_data_coleta timestamp;
BEGIN

  -- Cria tabela de backup
  DROP TABLE IF EXISTS computador_coleta_historico_bak;
  CREATE TABLE computador_coleta_historico_bak AS
    select id_computador,
      id_computador_coleta,
      id_class_property,
      te_class_property_value,
      max(dt_hr_inclusao) as dt_hr_inclusao
    from computador_coleta_historico
    WHERE dt_hr_inclusao is NOT NULL
    group by id_computador,
      id_computador_coleta,
      id_class_property,
      te_class_property_value;


  -- Apaga todos os registros da tabela anterior
  DROP TABLE computador_coleta_historico;

  -- Prepara para inserir de volta na tabela
  IF NOT EXISTS (
      SELECT 1
      FROM   pg_class c
        JOIN   pg_namespace n ON n.oid = c.relnamespace
      WHERE  c.relname = 'computador_coleta_historico_id_computador_coleta_historico_seq'
  ) THEN
    CREATE SEQUENCE computador_coleta_historico_id_computador_coleta_historico_seq;
  ELSE  
    PERFORM setval('computador_coleta_historico_id_computador_coleta_historico_seq', 1);
  END IF;

  ALTER TABLE computador_coleta_historico_bak
  ADD COLUMN id_computador_coleta_historico INTEGER
  DEFAULT nextval('computador_coleta_historico_id_computador_coleta_historico_seq');

  CREATE  INDEX computador_coleta_historico_bak_value_idx ON
    computador_coleta_historico_bak (
      id_computador_coleta,
      id_computador,
      id_class_property,
      te_class_property_value
    );

  RAISE NOTICE 'Criação do índice concluída! Continuando...';

  -- Primeiro descobre os nomes de propriedades repetidos
  FOR prop IN select *
              from computador_coleta_historico_bak LOOP

    RAISE NOTICE 'Coletas repetidas encontradas para id_computador_coleta = % e te_class_property_value = %',
      prop.id_computador_coleta,
      prop.te_class_property_value;

    UPDATE computador_coleta_historico_bak
    SET id_computador_coleta_historico = nextval('computador_coleta_historico_id_computador_coleta_historico_seq')
    WHERE id_computador_coleta = prop.id_computador_coleta
          AND id_class_property = prop.id_class_property
          AND id_computador = prop.id_computador
          AND te_class_property_value = prop.te_class_property_value;

  END LOOP;

  -- Finaliza e consolida alterações
  RAISE NOTICE 'Atualização finalizada! Alterando nome da tabela de volta!';

  ALTER TABLE computador_coleta_historico_bak RENAME TO computador_coleta_historico;

  IF NOT EXISTS (
      SELECT 1
      FROM   pg_class c
        JOIN   pg_namespace n ON n.oid = c.relnamespace
      WHERE  c.relname = 'computador_coleta_historico_value_idx'
  ) THEN
    create UNIQUE INDEX computador_coleta_historico_value_idx
    on computador_coleta_historico (
      id_computador_coleta,
      id_computador,
      id_class_property,
      te_class_property_value
    );
  END IF;

  RETURN;
END;
$$ LANGUAGE 'plpgsql';
