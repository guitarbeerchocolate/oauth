<?php
class config
{
	public $values = NULL;
	function __construct()
	{
		$this->values = (object) parse_ini_file('classes/config.ini', true);		
	}	
}
?>