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
	public static function setData($key,$value,$overwrite=true)
	{
		$methodName=self::getCurrentTest();
		if($overwrite){
			self::$messageData[$methodName][$key]=$value;
		}else{
			// get the current value and append...
			$currentData=self::getData($key);
			$currentData[$key]=$value;
			self::$messageData[$methodName][$key]=$currentData;
		}
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