<?php
class database
{
	private $config;
	private $connection;
	function __construct()
	{
		$this->config = new config;
		$link = mysql_connect($this->config->values->DB_HOST, $this->config->values->DB_USERNAME, $this->config->values->DB_PASSWORD);
		$this->connection = mysql_select_db($this->config->values->DB_NAME);
	}

	function query($q)
	{
		$objArray = array();
		$result = mysql_query($q);
		while($row = mysql_fetch_object($result))
		{
			array_push($objArray, $row);
		}
		return (object) $objArray;
	}

	function singleRow($q)
	{
		$result = mysql_query($q);
		return mysql_fetch_object($result);
	}

	function lastAdded()
	{
		return mysql_insert_id();
	}

	function __destruct()
	{
		mysql_close();
	}
}
?>