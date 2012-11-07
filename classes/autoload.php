<?php
function __autoload($classname)
{
	require_once $classname.'.class.php';
}
?>