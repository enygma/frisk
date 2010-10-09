<?php

/**
 * Storage class for data for each test and
 * between method calls inside
 * 
 * @author Chris Cornutt <ccornutt@phpdeveloper.org>
 * @package Frisk
 *
 */
class Message 
{
	static $messageData 		= array();
	static $currentTestMethod 	= null;
	
	public static function setCurrentTest($methodName)
	{
		self::$currentTestMethod = $methodName;
	}
	public static function getCurrentTest()
	{
		return self::$currentTestMethod;
	}
	public static function setData($key,$value)
	{
		$methodName=self::getCurrentTest();
		self::$messageData[$methodName][$key]=$value;
	}
	public static function getData($key)
	{
		$methodName=self::getCurrentTest();
		if(isset(self::$messageData[$methodName][$key])){
			return self::$messageData[$methodName][$key];
		}else{ return null; }
	}
}

?>