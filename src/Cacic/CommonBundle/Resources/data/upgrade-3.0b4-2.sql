CREATE OR REPLACE FUNCTION upgrade() RETURNS VOID AS $$
  DECLARE

    soft        record;
    nm          integer;
    atualiza    record;
    comp  integer;

  BEGIN

    FOR soft IN select sw.nm_software, count(distinct sw.id_software) as n_repeticoes
      from software sw
      group by sw.nm_software
      having count(distinct sw.id_software) > 1
      order by count(distinct sw.id_software) desc LOOP

      RAISE NOTICE 'O seguinte software possui entradas repetidas: %',soft.nm_software;

      -- Escolhe um software para colocar em todos
      SELECT DISTINCT id_software INTO nm
      FROM software
      WHERE nm_software = soft.nm_software
      ORDER BY id_software asc
      LIMIT 1;

      -- Atualiza o valor de todas as entradas na tabela para o primeiro valor
      FOR atualiza IN select distinct pr.id_propriedade_software, pr.id_class_property, pr.id_software, aq.id_software
        from software st
        inner join proriedade_software pr on st.id_software = pr.id_software
        left join aquisicao_item aq on st.id_software = aq.id_software
        where st.nm_software = soft.nm_software
        and st.id_software <> nm LOOP

        RAISE NOTICE 'Removendo propriedade = % com o software = %',atualiza.id_class_property,nm;

        DELETE FROM proriedade_software
        WHERE id_class_property = atualiza.id_class_property;

        DELETE FROM computador_coleta_historico
        WHERE id_class_property = atualiza.id_class_property;

        DELETE FROM computador_coleta
        WHERE id_class_property = atualiza.id_class_property;

        DELETE FROM class_property
        WHERE id_class_property = atualiza.id_class_property;

        IF atualiza.id_software  IS NOT NULL THEN
          DELETE FROM aquisicao_item
          WHERE id_software = atualiza.id_software;
        END IF;

        DELETE FROM software
        WHERE id_software = atualiza.id_software;

        RAISE NOTICE 'Software = % com o nome = % foi removido com sucesso',atualiza.id_software,soft.nm_software;

      END LOOP;

    END LOOP;

    RETURN;

  END;

$$ LANGUAGE 'plpgsql';