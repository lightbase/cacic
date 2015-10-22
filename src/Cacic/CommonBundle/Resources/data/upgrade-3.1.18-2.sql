CREATE OR REPLACE FUNCTION update_licencas() RETURNS VOID AS $$
DECLARE

BEGIN

  DROP TABLE IF EXISTS software_licencas;

  CREATE TABLE software_licencas AS
    SELECT
      row_number() OVER (
        ORDER BY
          c3_.id_computador ASC,
          max(c9_.dt_hr_inclusao) DESC
        ) as id_software_licenca,
      a0_.id_aquisicao,
      a2_.id_aquisicao_item,
      t1_.id_tipo_licenca,
      c3_.id_computador,
      c3_.ativo as comp_ativo,
      prop.ativo as prop_ativo,
      max(c9_.dt_hr_inclusao) as data_coleta
    FROM
      aquisicao a0_
      INNER JOIN aquisicao_item a2_ ON a0_.id_aquisicao = a2_.id_aquisicao
      INNER JOIN tipo_licenca t1_ ON a2_.id_tipo_licenca = t1_.id_tipo_licenca
      INNER JOIN aquisicoes_software a5_ ON a2_.id_aquisicao_item = a5_.id_aquisicao_item
      INNER JOIN software_relatorio s4_ ON s4_.id_relatorio = a5_.id_relatorio
      INNER JOIN relatorios_software r7_ ON s4_.id_relatorio = r7_.id_relatorio
      INNER JOIN software s6_ ON s6_.id_software = r7_.id_software
      INNER JOIN proriedade_software p8_ ON (
        s6_.id_software = p8_.id_software
      )
      INNER JOIN class_property prop ON p8_.id_class_property = prop.id_class_property
      INNER JOIN computador_coleta c9_ ON (
        p8_.id_computador = c9_.id_computador
        AND p8_.id_class_property = c9_.id_class_property
      )
      INNER JOIN computador c3_ ON (
        p8_.id_computador = c3_.id_computador
      )
      LEFT JOIN relatorios_software exc ON  (
        s6_.id_software = exc.id_software
      )
      LEFT JOIN software_relatorio rel_exc ON (
        exc.id_relatorio = rel_exc.id_relatorio
        AND rel_exc.tipo = 'excluir'
      )

    WHERE (s4_.tipo <> 'excluir' OR s4_.tipo IS NULL)
      AND rel_exc.id_relatorio IS NULL

    GROUP BY
      a0_.id_aquisicao,
      a2_.id_aquisicao_item,
      t1_.id_tipo_licenca,
      c3_.id_computador,
      c3_.ativo,
      prop.ativo,
      rel_exc.id_relatorio
    ORDER BY
      c3_.id_computador ASC,
      data_coleta DESC;


  -- Cria índices
  CREATE INDEX software_licencas_data_idx
  ON software_licencas (data_coleta);

  ALTER TABLE software_licencas
  ADD PRIMARY KEY (id_software_licenca);

  ALTER TABLE software_licencas
  ADD FOREIGN KEY (id_computador)
  REFERENCES computador (id_computador);

  ALTER TABLE software_licencas
  ADD FOREIGN KEY (id_aquisicao)
  REFERENCES aquisicao (id_aquisicao);

  ALTER TABLE software_licencas
  ADD FOREIGN KEY (id_tipo_licenca)
  REFERENCES tipo_licenca (id_tipo_licenca);

  --ALTER TABLE software_licencas
  --ADD FOREIGN KEY (id_aquisicao_item)
  --REFERENCES aquisicao_item (id_aquisicao_item);

  RETURN;
END;
$$ LANGUAGE 'plpgsql';