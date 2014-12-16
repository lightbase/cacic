CREATE OR REPLACE FUNCTION upgrade() RETURNS VOID AS $$
  DECLARE

    comp      record;

  BEGIN

    FOR comp IN select c.id_computador_coleta, max(h.dt_hr_inclusao) as dt_hr_inclusao
      from computador_coleta c
      inner join computador_coleta_historico h on (c.id_computador_coleta = h.id_computador_coleta)
      group by c.id_computador_coleta LOOP

      RAISE NOTICE 'Atualizando computador coleta id = %',comp.id_computador_coleta;

      UPDATE computador_coleta
      SET dt_hr_inclusao = comp.dt_hr_inclusao
      WHERE id_computador_coleta = comp.id_computador_coleta;

    END LOOP;

    RETURN;

  END;

$$ LANGUAGE 'plpgsql';