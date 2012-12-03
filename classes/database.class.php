<?php
class database
{
	private $config;
	private $connection;
	private $pdoString;
	function __construct()
	{
		$this->config = new config;
		$this->config->values->DB_TYPE;
		$this->pdoString = $this->config->values->DB_TYPE;
		$this->pdoString .= ':dbname='.$this->config->values->DB_NAME;
		$this->pdoString .= ';host='.$this->config->values->DB_HOST;
		$this->connection = new PDO($this->pdoString, $this->config->values->DB_USERNAME, $this->config->values->DB_PASSWORD);		
	}

	/* Example usage
	$results = $db->query("SELECT * FROM `users`");
	foreach($results as $row)
	{
		echo $row->id.'<br />';
	} */
	public function query($q)
	{
		$statement = $this->connection->query($q);
		$statement->setFetchMode(PDO::FETCH_OBJ);		
		return $statement->fetchAll();
	}

	/* Example usage
	$result = $db->singleRow("SELECT * FROM `users` WHERE `id`='2'");
	echo $result->username;
	*/
	public function singleRow($q)
	{
		$sth = $this->connection->prepare($q);
		$sth->execute();		
		return (object)  $sth->fetch();
	}

	function __destruct()
	{
		$this->connection = NULL;
	}
}
?>