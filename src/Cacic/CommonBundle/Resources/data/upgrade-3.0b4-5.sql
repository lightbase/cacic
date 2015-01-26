CREATE OR REPLACE FUNCTION upgrade() RETURNS VOID AS $$
  DECLARE

    printer         record;
    counter         record;
    ser           record;
    printerid      integer;

  BEGIN

    FOR ser IN select distinct serie from tb_printer LOOP

        SELECT max(id) INTO printerid
        FROM tb_printer
        WHERE serie = ser.serie;

        FOR printer IN select * from tb_printer where serie = ser.serie and id <> printerid LOOP

          RAISE NOTICE 'Impressora repetida: %. Apagando todos os contadores', printerid;

            FOR counter IN SELECT *
              FROM tb_printer_counter
              WHERE printer_id = printer.id LOOP

              -- Copia os contadores para a impressora anterior
              BEGIN
                UPDATE tb_printer_counter
                SET printer_id = printerid
                WHERE id = counter.id;

              EXCEPTION
                WHEN SQLSTATE '23505' THEN
                  RAISE NOTICE 'Contador e datas repetidos para id %. Removendo contador...', counter.id;
                  DELETE FROM tb_printer_counter WHERE id = counter.id;
              END;

            END LOOP;

            -- Agora apaga impressora
            RAISE NOTICE 'Removendo impressora %', printerid;
            DELETE FROM tb_printer WHERE id = printer.id;

        END LOOP;

    END LOOP;

    -- Agora cria coluna contendo o número de série da SIMPRESS
    BEGIN 
      ALTER TABLE tb_printer
      ADD COLUMN serie_simpress VARCHAR;

    EXCEPTION
      WHEN SQLSTATE '42701' THEN
        RAISE NOTICE 'Coluna série simpress já existe';

    END;

    BEGIN
      ALTER TABLE tb_printer
      ADD COLUMN active BOOLEAN;

    EXCEPTION
      WHEN SQLSTATE '42701' THEN
        RAISE NOTICE 'Coluna série active já existe';

    END;

    FOR printer IN SELECT * from tb_printer LOOP
      RAISE NOTICE 'Adiciona número de série SIMPRESS para impressora %', printer.serie;

      UPDATE tb_printer
      SET serie_simpress = substring(printer.serie from 1 for 14)
      WHERE id = printer.id;

    END LOOP;

    RETURN;

  END;

$$ LANGUAGE 'plpgsql';
