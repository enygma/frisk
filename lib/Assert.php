<?php
/* Abstract assertion class */

abstract class Assert
{
	abstract protected function assertExecute();
	abstract protected function assertSetup();
	abstract protected function assertTeardown();
	
	/**
	* Assertion arguments
	* @var array
	*/
	public $assertArguments 	= array();

	/**
	* Assert arguments (incoming from call)
	* @var array
	*/
	static $currentArguments 	= null;

	/**
	* Current HTTP connection resource (HttpMessage)
	* @var object
	*/
	static $currentHttp 		= null;
	
	/**
	* Create the Assert object and set some based variables
	*
	* @return void
	*/
	public function __construct($http,$args)
	{
		self::$currentArguments	= $args;
		self::$currentHttp 	= $http;
	}
}

?>
