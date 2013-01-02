<?php
require_once('Relatorio.php');

class RelatorioCSV extends Relatorio
{

    public function output()
	{
		$buffer = "";
		//Data
		foreach ($this->getBody() as $row)
		{
			$line = array();
			foreach ($row as $cell)
			{
				$line[] = '"'.str_replace('"', '""', iconv('utf-8' ,'iso-8859-1', $this->relstrip($cell))).'"';
			}
			$buffer .= implode(",", $line) . "\r\n";
		}

		if (ob_get_contents())
		{
			die('Saída já iniciada, impossível emitir CSV: ['.ob_get_contents().']');
		}
		if(isset($_SERVER['HTTP_USER_AGENT']) && strpos($_SERVER['HTTP_USER_AGENT'],'MSIE'))
		{
			header('Content-Type: application/force-download');
		}
		else
		{
			header('Content-Type: application/octet-stream');
		}		
		if (headers_sent())
		{
			die('Saída já iniciada, impossível emitir CSV.');
		}
		header('Content-Length: '.strlen($buffer));
		header('Content-disposition: attachment; filename="relatorio.csv"');
		echo $buffer;
	}
}
?>
