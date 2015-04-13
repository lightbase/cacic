CREATE OR REPLACE FUNCTION upgrade_software() RETURNS VOID AS $$    
  DECLARE
    soft          RECORD;
    prop_software INTEGER;
  BEGIN

    FOR soft IN select prop.id_class_property,
                      comp.id_computador,
                      sw.id_software,
                      cl.te_class_property_value,
                      sw.te_descricao_software
                    from computador_coleta cl
                    inner join computador comp on cl.id_computador = comp.id_computador
                    INNER JOIN class_property prop on cl.id_class_property = prop.id_class_property
                    INNER JOIN classe on prop.id_class = classe.id_class
                    INNER JOIN software sw on sw.nm_software = prop.nm_property_name
                    WHERE classe.nm_class_name = 'SoftwareList' LOOP

      SELECT pr.id_propriedade_software INTO prop_software
      FROM proriedade_software pr
      WHERE pr.id_computador = soft.id_computador
      AND pr.id_software = soft.id_software
      AND pr.id_class_property = soft.id_class_property;

      RAISE NOTICE '1111111111111111111111111111111 | %',prop_software;

      IF prop_software IS NULL THEN

        RAISE NOTICE 'Inserindo software = % em computador = %',soft.id_software,soft.id_computador;

        INSERT INTO proriedade_software (
          id_computador,
          id_class_property,
          id_software
        ) VALUES (
          soft.id_computador,
          soft.id_class_property,
          soft.id_software
        );

      END IF;

    END LOOP;
    
    RETURN;
  END;
$$ LANGUAGE 'plpgsql';