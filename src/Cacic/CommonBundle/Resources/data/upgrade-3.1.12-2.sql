CREATE OR REPLACE FUNCTION upgrade_software2() RETURNS VOID AS $$
  DECLARE
    soft          RECORD;
    prop_software RECORD;
    comp          RECORD;
    v_id_software INTEGER;
  BEGIN

    FOR soft IN select  count(distinct id_software) as n_software,
                  display_name
                from proriedade_software
                where display_name is not null
                and display_name <> ''
                group by display_name
                having count(distinct id_software) > 1
                order by n_software desc LOOP

      RAISE NOTICE 'Atualizando nome do software para software = %', soft.display_name;

      -- Primeira busca pelo nome do software
      SELECT id_software INTO v_id_software
      FROM software
      WHERE nm_software = soft.display_name;

      IF v_id_software IS NULL THEN
        -- Considera somente o primeiro software com o nome
        SELECT min(id_software) INTO v_id_software
        FROM proriedade_software
        WHERE display_name = soft.display_name;
      END IF;

      -- Atualiza todas as entradas para o mínimo id_software
      FOR prop_software IN select distinct id_software
                           from proriedade_software
                           WHERE display_name = soft.display_name
                           and id_software <> v_id_software LOOP

        RAISE NOTICE 'Removendo id_software = % e atualizando para id_software = %',prop_software.id_software,v_id_software;

        -- Atualiza propriedade de software
        FOR comp IN select id_class_property,
                      id_computador,
                      id_propriedade_software
                    from proriedade_software
                    WHERE id_software = prop_software.id_software LOOP

          BEGIN
            UPDATE proriedade_software
            SET id_software = v_id_software
            WHERE id_software = prop_software.id_software
            AND id_computador = comp.id_computador
            AND id_class_property = comp.id_class_property;

            EXCEPTION
            WHEN SQLSTATE '23505' THEN
              RAISE NOTICE 'Coleta de software já identificada para o computador no id %. Removendo...', comp.id_propriedade_software;
              DELETE FROM proriedade_software WHERE id_propriedade_software = comp.id_propriedade_software;
          END;

        END LOOP;

        -- Atualiza aquisições de software
        BEGIN
          UPDATE aquisicoes_software
          SET id_software = v_id_software
          WHERE id_software = prop_software.id_software;

        EXCEPTION
          WHEN SQLSTATE '23505' THEN
            RAISE NOTICE 'Software já cadastrado no processo de aquisição. Continua...';

            -- Apaga aquisições de qualquer jeito
            DELETE FROM aquisicoes_software
            WHERE id_software = prop_software.id_software;

        END;

        -- Agora apaga software
        DELETE FROM software
        WHERE id_software = prop_software.id_software;

        -- Atualiza nome do software
        UPDATE software
        SET nm_software = soft.display_name
        WHERE id_software = v_id_software;

      END LOOP;

    END LOOP;

    RETURN;
  END;
$$ LANGUAGE 'plpgsql';