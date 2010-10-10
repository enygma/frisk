<?php

abstract class Action
{

	static $currentMessage		= null;
	
	/** 
	* Create the object and set the base variables
	*
	* @return void
	*/
	public function __construct($http,$args)
	{
		//nothing to see...
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
