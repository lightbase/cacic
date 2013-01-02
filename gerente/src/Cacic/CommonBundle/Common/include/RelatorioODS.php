<?php
require_once('Relatorio.php');
require_once("ods.php");

class RelatorioODS extends Relatorio
{
    public function output()
	{
		$ods = newOds();

		$i = 0;
		foreach ($this->getBody() as $row)
		{
			$j = 0;
			foreach ($row as $cell)
			{
				$ods->addCell(0, $i, $j++, iconv('iso-8859-1' ,'utf-8', str_ireplace('\n', '', $this->relstrip($cell))), 'string'); 
			}
			$i++;
		}
		$ods->output();
	}
}
?>
