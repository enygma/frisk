<?php
/* Abstract assertion class */

abstract class Assert
{
	abstract protected function assertExecute();
	abstract protected function assertSetup();
	abstract protected function assertTeardown();
	
	static $currentMessage		= null;
	
	/**
	* Create the Assert object
	*
	* @return void
	*/
	public function __construct($http,$args)
	{
		// nothing to see...
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
