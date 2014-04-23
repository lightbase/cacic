CREATE OR REPLACE FUNCTION upgrade() RETURNS VOID AS $$
  DECLARE

    soft        record;
    nm          integer;
    atualiza    record;
    comp  integer;

  BEGIN

    FOR soft IN select sw.nm_software, count(prop.id_propriedade_software) as n_repeticoes
      from software sw
      inner join proriedade_software prop on sw.id_software = prop.id_software
      group by sw.nm_software
      having count(prop.id_propriedade_software) > 1
      order by count(prop.id_propriedade_software) desc LOOP

      RAISE NOTICE 'O seguinte software possui entradas repetidas: %',soft.nm_software;

      -- Escolhe um software para colocar em todos
      SELECT DISTINCT id_software INTO nm
      FROM software
      WHERE nm_software = soft.nm_software
      ORDER BY id_software asc
      LIMIT 1;

      -- Atualiza o valor de todas as entradas na tabela para o primeiro valor
      FOR atualiza IN select distinct pr.id_propriedade_software
        from software st
        inner join proriedade_software pr on st.id_software = pr.id_software
        where st.nm_software = soft.nm_software LOOP


        RAISE NOTICE 'Atualizando o valor da propriedade = % com o software = %',atualiza.id_propriedade_software,nm;

        BEGIN

          UPDATE proriedade_software
            SET id_software = nm
            WHERE id_propriedade_software = atualiza.id_propriedade_software;

        EXCEPTION WHEN OTHERS THEN

          RAISE NOTICE 'ERRO!!!! Provavelmente a coleta estava repetida';

        END;

      END LOOP ;

    END LOOP;

    -- Finalmente limpa todos os softwares sem coleta

    FOR soft IN select distinct sw.id_software
      from software sw
      left join proriedade_software prop on sw.id_software = prop.id_software
      left join aquisicao_item aq on sw.id_software = aq.id_software
      where prop.id_software is null
      and aq.id_software is null LOOP

      RAISE NOTICE 'Removendo software = %',soft.id_software;

      BEGIN

        DELETE FROM software
        WHERE id_software = soft.id_software;

      EXCEPTION WHEN OTHERS THEN

        RAISE NOTICE 'Erro na exclusão do software %',soft.id_software;

      END ;

    END LOOP ;


    RETURN;

  END;

$$ LANGUAGE 'plpgsql';