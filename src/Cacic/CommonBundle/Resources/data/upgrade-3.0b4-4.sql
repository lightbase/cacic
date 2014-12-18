CREATE OR REPLACE FUNCTION gera_relatorio_wmi() RETURNS VOID AS $$
  DECLARE

    comp        record;
    prop        record;
    classe      record;
    sql         varchar;
    column_name varchar;

  BEGIN

    DROP TABLE IF EXISTS relatorio_coleta;

    CREATE TABLE relatorio_coleta (
        id_computador INTEGER  REFERENCES computador(id_computador) PRIMARY KEY NOT NULL,
        data_coleta TIMESTAMP
    );

    CREATE INDEX id_computador_idx ON relatorio_coleta(id_computador);
    CREATE INDEX data_coleta_idx ON relatorio_coleta(data_coleta);

    -- Cria estrutura da tabela com dados de todas as coletas
    FOR classe IN select *
      from classe
      where nm_class_name <> 'SoftwareList' LOOP

      RAISE NOTICE 'Adicionando classe % na tabela', classe.id_class;

      FOR prop IN select distinct nm_property_name
        from class_property
        where id_class = classe.id_class LOOP

          sql := 'ALTER TABLE relatorio_coleta
          ADD COLUMN '||lower(classe.nm_class_name)||'_'||lower(prop.nm_property_name)||' VARCHAR;
          ALTER TABLE relatorio_coleta
          ADD COLUMN '||lower(classe.nm_class_name)||'_'||lower(prop.nm_property_name)||'_data VARCHAR';

          RAISE NOTICE 'Executando SQL para adição de propriedade %', sql;

        BEGIN
          EXECUTE sql;
        EXCEPTION
          WHEN SQLSTATE '42701' THEN
            RAISE NOTICE 'Coluna % repetida', lower(prop.nm_property_name);
        END;

      END LOOP;

    END LOOP;

    -- Povoa tabela com todos os computadores
    FOR comp IN select distinct id_computador
      from computador_coleta LOOP

      RAISE NOTICE 'Adicionando computador id = %', comp.id_computador;

      INSERT INTO relatorio_coleta (id_computador)
      VALUES (comp.id_computador);

    END LOOP;


    FOR comp IN select c.id_computador_coleta, c.dt_hr_inclusao, c.id_computador, p.nm_property_name, cl.nm_class_name, c.te_class_property_value
      from computador_coleta c
      inner join class_property p on c.id_class_property =  p.id_class_property
      inner join classe cl on p.id_class = cl.id_class
      where cl.nm_class_name <> 'SoftwareList' LOOP

      --RAISE NOTICE 'Atualizando computador coleta id = %',comp.id_computador_coleta;

      column_name := lower(comp.nm_class_name)||'_'||lower(comp.nm_property_name);

      sql := 'UPDATE relatorio_coleta
      SET '||column_name||' = '''||trim(both '''' from comp.te_class_property_value)||''',
      '||column_name||'_data = '''||comp.dt_hr_inclusao||''',
      data_coleta = '''||comp.dt_hr_inclusao||'''
      WHERE id_computador = '||comp.id_computador||';';

      RAISE NOTICE 'Inserindo coleta %',sql;

      BEGIN
        EXECUTE sql;
      EXCEPTION
        WHEN SQLSTATE '42601' THEN
          RAISE NOTICE 'Caractere invalido na inserçao. Continuando...';
      END;

    END LOOP;

    RETURN;

  END;

$$ LANGUAGE 'plpgsql';