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
	
	/**
	 * If argument "config" exists, load in the file and parse config
	 *
	 * @param array $optionalConfig Optional configuration values (not implemented)
	 * @return void
	 */
	public function execute($optionalConfig=null)
	{
		// if the "config" value of the arguments is set, look for that file....
		if($configFile=HelperArguments::getArgument('config')){
			self::loadConfig($configFile);
		}
	}
	
	/**
	 * Load in the configuration information from a file path
	 *
	 * @param string $configFilePath Configuration file, full path
	 * @throws Exception
	 * @return void
	 */
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
	
	/**
	 * Set a configuration value into the array
	 * 
	 * @param string $name Configuration key name
	 * @param mixed $value Configuration value
	 * @return void
	 */
	public static function setConfigValue($name,$value)
	{
		self::$currentConfig[$name]=$value;
	}
	
	/**
	 * Remove a configuration value from the array
	 *
	 * @param string $name Configuration key name
	 */
	public static function deleteConfigValue($name)
	{
		unset(self::$currentConfig[$name]);
	}
	
	/**
	 * Return the current value of a configuration key
	 *
	 * @param string $name Configuration key name
	 */
	public static function getConfigValue($name)
	{
		return (isset(self::$currentConfig[$name])) ? self::$currentConfig[$name] : null;
	}
}

?>