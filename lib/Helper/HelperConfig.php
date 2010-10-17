<?php
/**
 * Helper for working with configuration values
 * 
 * @author Chris Cornutt <ccornutt@phpdeveloper.org>
 * @package Frisk
 */
class HelperConfig extends Helper
{
	/**
	 * Current configuration container
	 */
	static $currentConfig	= array();
	
	public function execute()
	{
		// if the "config" value of the arguments is set, look for that file....
		if($configFile=HelperArguments::get('config')){
			
		}else{
			// load in default settings
			
		}
	}
	
	public static function setConfigValue($name,$value)
	{
		self::$currentConfig[$name]=$value;
	}
	
	public static function deleteConfigValue($name)
	{
		unset(self::$currentConfig[$name]);
	}
	
	public static function getConfigValue($name)
	{
		return (isset(self::$currentConfig[$name])) ? self::$currentConfig[$name] : null;
	}
}

?>