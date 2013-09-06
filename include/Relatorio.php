<?php
abstract class Relatorio
{
	private $m_titulo;
	private $m_header;
	private $m_body = array();
	private $m_rowAttr = array();
	private $m_cellColor = array();

	public function setTitulo($titulo)
	{
		$this->m_titulo = $titulo;
	}

	public function getTitulo()
	{
		return $this->m_titulo;
	}

	public function setRowColor($row, $r, $g, $b)
	{
		$this->m_rowAttr[$row] = array($r, $g, $b);
	}

	public function getRowColor($row)
	{
		return $this->m_rowAttr[$row];
	}

	public function setCellColor($row, $col, $rgb)
	{
		$this->m_cellColor[$row][$col] = $rgb;
	}

	public function getCellColor($row, $col)
	{
		if (!isset($this->m_cellColor[$row][$col]))
		{
			return FALSE;
		}
		return $this->m_cellColor[$row][$col];
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
