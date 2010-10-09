<?php

abstract class Action
{
	/**
	* Current Action arguments (incoming from call)
	* @var array
	*/
	static $currentArguments 	= null;

	/**
	* Current HTTP object (HttpMessage)
	* @var object
	*/
	static $currentHttp 		= null;
	static $optionalArguments	= null;
	
	static $currentMessage		= null;
	
	/** 
	* Create the object and set the base variables
	*
	* @return void
	*/
	public function __construct($http,$args)
	{
		self::$currentArguments	= $args;
		self::$currentHttp 	= $http;
	}

	/**
	* Allow the setting of a global level variable
	* NOTE: This will be carried between Actions
	*
	* @return void
	*/
	public function setParentArgument($key,$value)
	{
		self::$optionalArguments[$key]=$value;
	}
	
	public static function setCurrentMessage(&$msgObj)
	{
		self::$currentMessage=$msgObj;
	}
	public static function getCurrentMessage()
	{
		return self::$currentMessage;
	}

}

?>
