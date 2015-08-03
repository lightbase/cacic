CREATE OR REPLACE FUNCTION upgrade_31172() RETURNS VOID AS $$
DECLARE
  prop record;
  soft record;
  v_software integer;
  v_ocorrencias integer;
BEGIN
  -- Agrupa software pelo identificador
  FOR prop in select count(id_software),
                id_class_property,
                id_computador
              from proriedade_software
              group by id_class_property,
                id_computador
              having count(id_software) > 1
              order by count(id_software) desc LOOP

    RAISE NOTICE 'Processando ocorrência id_class_property = % e id_computador = %', prop.id_class_property, prop.id_computador;

    -- Mantém a ocorrência mais recente
    SELECT min(id_software) INTO v_software
    FROM proriedade_software
    WHERE id_class_property = prop.id_class_property;

    FOR soft IN select id_software,
                  id_class_property
                from proriedade_software
                where id_software <> v_software
                      and id_class_property = prop.id_class_property LOOP

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

      -- Apagando software repetido
        RAISE NOTICE 'Removendo software repetido = %. Mantendo software atual = %', soft.id_software, v_software;

      DELETE FROM proriedade_software
      WHERE id_software = soft.id_software;

      DELETE FROM software
      WHERE id_software = soft.id_software;

    END LOOP;

  END LOOP;

  RETURN;
END;
$$ LANGUAGE 'plpgsql';