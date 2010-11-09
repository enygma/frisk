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
		'main'=>array('tests_directory' => './tests')
	);
	
	/**
	 * If argument "config" exists, load in the file and parse config
	 *
	 * @param array $optionalConfig Optional configuration values (not implemented)
	 * @return void
	 */
	public function execute($optionalConfig=null,$configKey='main')
	{
		// if the "config" value of the arguments is set, look for that file....
		if($configFile=HelperArguments::getArgument('config') || $optionalConfig!=null){
			if($optionalConfig!=null){ $configFile=$optionalConfig; }
			try {
				self::loadConfig($configFile,$configKey);
			}catch(Exception $e){
				throw new Exception($e->getMessage());
			}
		}
	}
	
	/**
	 * Load in the configuration information from a file path
	 *
	 * @param string $configFilePath Configuration file, full path
	 * @throws Exception
	 * @return void
	 */
	private function loadConfig($configFilePath,$configKey='main')
	{
		if(is_file($configFilePath)){
			$configSettings = parse_ini_file($configFilePath,true);
			
			// If there's a colon in the $sectionName, use it as a type
			foreach($configSettings as $sectionName => $sectionValue){
				if(strpos($sectionName,':')!=false){
					$parts 		= explode(':',$sectionName);
					$settings 	= array();
					foreach($sectionValue as $settingName => $settingValue){
						$settings[$settingName]=$settingValue;
					}
					$settings['name']=$parts[1];
					HelperConfig::appendConfigValue($parts[0],$settings,$configKey);
				}else{
					foreach($sectionValue as $settingName => $settingValue){
						$configName = $sectionName.'_'.$settingName;
						HelperConfig::setConfigValue($configName,$settingValue,$configKey);
					}
				}
			}
		}else{
			throw new Exception(get_class().': Config file not found at '.$configFilePath.'!');
		}
	}
	
	/**
	 * Set a configuration value into the array
	 * 
	 * @param string $name Configuration key name
	 * @param mixed $value Configuration value
	 * @return void
	 */
	public static function setConfigValue($name,$value,$configKey='main')
	{
		self::$currentConfig[$configKey][$name]=$value;
	}

	public static function appendConfigValue($name,$value,$configKey='main')
	{
		self::$currentConfig[$configKey][$name][]=$value;
	}
	
	/**
	 * Remove a configuration value from the array
	 *
	 * @param string $name Configuration key name
	 */
	public static function deleteConfigValue($name,$configKey='main')
	{
		unset(self::$currentConfig[$configKey][$name]);
	}
	
	/**
	 * Return the current value of a configuration key
	 *
	 * @param string $name Configuration key name
	 */
	public static function getConfigValue($name,$configKey='main')
	{
		return (isset(self::$currentConfig[$configKey][$name])) ? self::$currentConfig[$configKey][$name] : null;
	}
	
	/**
	 * Return the full set of values in a configuration key/section
	 *
	 * @param string $configKey Configuration key to return values from
	 */
	public static function getConfigKeyValues($configKey='main')
	{
		return (isset(self::$currentConfig[$configKey])) ? self::$currentConfig[$configKey] : null;
	}
}

?>
