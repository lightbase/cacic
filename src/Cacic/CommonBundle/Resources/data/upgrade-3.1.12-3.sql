CREATE OR REPLACE FUNCTION remove_repetidos(INTEGER, INTEGER) RETURNS VOID AS $$
  DECLARE
    so_velho      ALIAS FOR $1;
    so_novo       ALIAS FOR $2;
    computadores          RECORD;
    computador_antigo   RECORD;
    v_id_software INTEGER;
    v_id_computador INTEGER;
  BEGIN

    RAISE NOTICE 'Atualizando valores de SO velho = % para SO novo = %', so_velho, so_novo;

    -- 1 - Encontra lista de computadores com MAC repetido
    FOR computadores IN select count(id_computador) as n_comp,
                          te_node_address
                        FROM computador
                        WHERE id_so = so_velho
                        GROUP BY te_node_address
                        HAVING count(id_computador) > 1
                        ORDER BY n_comp LOOP

      SELECT id_computador INTO v_id_computador
      FROM computador
      WHERE id_so = so_velho
      AND te_node_address = computadores.te_node_address
      ORDER BY dt_hr_ult_acesso DESC
      limit 1;

      FOR computador_antigo IN  SELECT id_computador
                                FROM computador
                                WHERE te_node_address = computadores.te_node_address
                                AND id_so = so_velho
                                and id_computador <> v_id_computador LOOP

        RAISE NOTICE 'Atualizando computador = % para o novo valor = %', computador_antigo.id_computador, v_id_computador;

        UPDATE log_acesso SET id_computador = v_id_computador WHERE id_computador = computador_antigo.id_computador;
        UPDATE log_user_logado SET id_computador = v_id_computador WHERE id_computador = computador_antigo.id_computador;

        DELETE FROM proriedade_software WHERE id_computador = computador_antigo.id_computador;
        DELETE FROM relatorio_coleta WHERE id_computador = computador_antigo.id_computador;
        DELETE FROM computador_coleta_historico WHERE id_computador = computador_antigo.id_computador;
        DELETE FROM computador_coleta WHERE id_computador = computador_antigo.id_computador;
        DELETE FROM rede_grupo_ftp WHERE id_computador = computador_antigo.id_computador;
        DELETE FROM computador WHERE id_computador = computador_antigo.id_computador;

      END LOOP;

    END LOOP;

    -- Agora remove MAC repetido
    FOR computadores in SELECT comp.id_computador,
                          comp.te_node_address,
                          comp.id_so,
                          comp2.te_node_address as te_node_address2,
                          array_agg(DISTINCT comp2.id_computador) AS id_computador2
                        FROM computador comp
                          LEFT JOIN computador comp2 ON (comp.te_node_address = comp2.te_node_address and comp.id_computador <> comp2.id_computador)
                        WHERE comp.id_so = so_novo
                        AND comp2.id_so = so_velho
                        AND comp2.id_computador IS NOT NULL
                        GROUP BY comp.id_computador,
                        comp.te_node_address,
                        comp.id_so,
                        comp2.te_node_address
                        ORDER BY comp.id_computador desc LOOP

      RAISE NOTICE 'Atualizando valores para o computador = % | MAC = %', computadores.id_computador, computadores.te_node_address;

      v_id_computador := computadores.id_computador;

      UPDATE log_acesso SET id_computador = v_id_computador WHERE id_computador = ANY (computadores.id_computador2);
      UPDATE log_user_logado SET id_computador = v_id_computador WHERE id_computador = ANY (computadores.id_computador2);

      DELETE FROM proriedade_software WHERE id_computador = ANY (computadores.id_computador2);
      DELETE FROM relatorio_coleta WHERE id_computador = ANY (computadores.id_computador2);
      DELETE FROM computador_coleta_historico WHERE id_computador = ANY (computadores.id_computador2);
      DELETE FROM computador_coleta WHERE id_computador = ANY (computadores.id_computador2);
      DELETE FROM rede_grupo_ftp WHERE id_computador = ANY (computadores.id_computador2);
      DELETE FROM computador WHERE id_computador = ANY (computadores.id_computador2);

    END LOOP;

    -- Agora pega todas as ocorrências com o SO Novo

    FOR computadores in SELECT comp.id_computador as id_computador,
                          comp.te_node_address,
                          comp.id_so,
                          comp2.te_node_address as te_node_address2,
                          comp2.id_computador AS id_computador2
                        FROM computador comp
                          LEFT JOIN computador comp2 ON (comp.te_node_address = comp2.te_node_address and comp.id_computador <> comp2.id_computador)
                        WHERE comp.id_so = so_velho
                              AND comp2.id_so = so_novo
                              AND comp2.id_computador IS NOT NULL
                        ORDER BY comp.id_computador desc LOOP

      RAISE NOTICE 'Atualizando valores para o computador = % | MAC = %', computadores.id_computador, computadores.te_node_address;

      v_id_computador := computadores.id_computador2;

      UPDATE log_acesso SET id_computador = v_id_computador WHERE id_computador = computadores.id_computador;
      UPDATE log_user_logado SET id_computador = v_id_computador WHERE id_computador = computadores.id_computador;

      DELETE FROM proriedade_software WHERE id_computador = computadores.id_computador;
      DELETE FROM relatorio_coleta WHERE id_computador = computadores.id_computador;
      DELETE FROM computador_coleta_historico WHERE id_computador = computadores.id_computador;
      DELETE FROM computador_coleta WHERE id_computador = computadores.id_computador;
      DELETE FROM rede_grupo_ftp WHERE id_computador = computadores.id_computador;
      DELETE FROM computador WHERE id_computador = computadores.id_computador;

    END LOOP;

    -- Finalmente remove e atualiza os valores
    UPDATE computador SET id_so = so_novo WHERE id_so = so_velho;
    UPDATE aplicativo SET id_so = so_novo WHERE id_so = so_velho;
    DELETE from acao_so WHERE id_so = so_velho;
    DELETE FROM so WHERE id_so = so_velho;

    RAISE NOTICE 'SO velho = % removido', so_velho;

    RETURN;
  END;
$$ LANGUAGE 'plpgsql';