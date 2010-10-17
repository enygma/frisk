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
	 * Sets up some defaults
	 */
	static $currentConfig	= array(
		'tests_directory' => './tests'
	);
	
	public function execute($optionalConfig)
	{
		// if the "config" value of the arguments is set, look for that file....
		if($configFile=HelperArguments::getArgument('config')){
			self::loadConfig($configFile);
		}
	}
	
	private function loadConfig($configFilePath)
	{
		if(is_file($configFilePath)){
			$configSettings = parse_ini_file($configFilePath,true);
			
			foreach($configSettings as $sectionName => $sectionValue){
				foreach($sectionValue as $settingName => $settingValue){
					$configName = $sectionName.'_'.$settingName;
					HelperConfig::setConfigValue($configName,$settingValue);
				}
			}
		}else{
			throw new Exception(get_class().': Config file not found!');
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