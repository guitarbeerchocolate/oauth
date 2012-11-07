<?php
class database
{
	private $config;
	private $connection;
	private $pdoString;
	function __construct()
	{
		$this->config = (object) parse_ini_file('config.ini', true);
		$this->pdoString = $this->config->DB_TYPE;
		$this->pdoString .= ':dbname='.$this->config->DB_NAME;
		$this->pdoString .= ';host='.$this->config->DB_HOST;
		$this->connection = new PDO($this->pdoString, $this->config->DB_USERNAME, $this->config->DB_PASSWORD);  
	}

	public function query($q)
	{
		return $this->connection->query($q);
	}

	public function singleRow($q)
	{
		$sth = $this->connection->prepare($q);
		$sth->execute();
		return (object) $sth->fetch();
	}

	function __destruct()
	{
		$this->connection = NULL;
	}
}
?>