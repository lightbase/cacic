<?php
abstract class Relatorio
{
	private $m_titulo;
	private $m_header;
	private $m_body = array();

	public function setTitulo($titulo)
	{
		$this->m_titulo = $titulo;
	}

	public function getTitulo()
	{
		return $this->m_titulo;
	}

	public function setTableHeader(array $header)
	{
		$this->m_header = $header;
	}

	public function addRow(array $row)
	{
		$this->m_body[] = $row;
	}

	public function getHeader()
	{
		return $this->m_header;
	}

	public function getBody()
	{
		return $this->m_body;
	}

	public function relstrip($html)
	{
		return strip_tags(str_ireplace('<br>', "\n", $html));
	}

    public abstract function output();
}
?>
