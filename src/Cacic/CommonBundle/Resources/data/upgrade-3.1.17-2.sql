CREATE OR REPLACE FUNCTION upgrade_31172() RETURNS VOID AS $$
DECLARE
  prop record;
  soft record;
  v_software integer;
  v_ocorrencias integer;
  v_column VARCHAR;
BEGIN
  -- Agrupa software pelo identificador
  FOR prop in select p.id_class_property,
                p.nm_property_name,
                p.id_class
              from class_property p
                INNER JOIN classe cl on p.id_class = cl.id_class
              where cl.nm_class_name = 'SoftwareList'
                and (p.nm_property_name is not null or p.nm_property_name <> '')  LOOP

    RAISE NOTICE 'Processando ocorrência id_class_property = % ', prop.id_class_property;

    -- Mantém a ocorrência mais recente
    SELECT min(id_software) INTO v_software
    FROM proriedade_software
    WHERE id_class_property = prop.id_class_property;

    -- Adiciona no identificador do software
    SELECT column_name INTO v_column
    FROM information_schema.columns
    WHERE table_name='software'
          and column_name='id_class_property';

    IF v_column IS NULL
      THEN
        -- Cria tabela como chave estrangeira e índice único
        ALTER TABLE software
        ADD id_class_property INT;

        ALTER TABLE software
        ADD CONSTRAINT FK_77D068CF25A6A88
        FOREIGN KEY (id_class_property)
        REFERENCES class_property (id_class_property);

        CREATE UNIQUE INDEX UNIQ_77D068CF25A6A88
        ON software (id_class_property);

    END IF;

    FOR soft IN select distinct id_software
                from proriedade_software
                where id_software <> v_software
                      and id_class_property = prop.id_class_property LOOP

      SELECT column_name INTO v_column
      FROM information_schema.columns
      WHERE table_name='aquisicoes_software'
            and column_name='id_software';

      IF v_column IS NOT NULL
        THEN

        BEGIN
          UPDATE aquisicoes_software
          SET id_software = v_software
          WHERE id_software = soft.id_software;

        EXCEPTION
          WHEN SQLSTATE '23505' THEN
            RAISE NOTICE 'Software já existente no relatório: % Software velho: %', v_software, soft.id_software;

          DELETE FROM aquisicoes_software
          WHERE id_software = soft.id_software;

        END;

      END IF;

      BEGIN
        UPDATE proriedade_software
        SET id_software = v_software
        WHERE id_software = soft.id_software;

      EXCEPTION
        WHEN SQLSTATE '23505' THEN
          RAISE NOTICE 'Software já existente na propriedade: % Software velho: %', v_software, soft.id_software;

        DELETE FROM proriedade_software
        WHERE id_software = soft.id_software;

      END;

      DELETE FROM software
      WHERE id_software = soft.id_software;

      -- Apagando software repetido
      RAISE NOTICE 'Removendo software repetido = %. Mantendo software atual = %', soft.id_software, v_software;

    END LOOP;

    -- Adiciona id_class_property no software
    UPDATE software
    SET id_class_property = prop.id_class_property
    WHERE id_software = v_software;

  END LOOP;

  RETURN;
END;
$$ LANGUAGE 'plpgsql';