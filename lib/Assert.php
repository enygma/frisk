<?php
/* Abstract assertion class */

abstract class Assert
{
	abstract protected function assertExecute();
	abstract protected function assertSetup();
	abstract protected function assertTeardown();
	
	public $assertArguments 	= array();
	static $currentArguments 	= null;
	static $currentHttp 		= null;
	
	public function __construct($http,$args){
		self::$currentArguments	= $args;
		self::$currentHttp 		= $http;
	}
}

?>
