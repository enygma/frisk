<?php

abstract class Action
{
	/* no abstract methods defined */
	static $currentArguments 	= null;
	static $currentHttp 		= null;
	
	public function __construct($http,$args){
		self::$currentArguments	= $args;
		self::$currentHttp 		= $http;
	}

}

?>