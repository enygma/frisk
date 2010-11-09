<?php
/**
 * Helper for working with test suites
 *
 * @author Chris Cornutt <ccornutt@phpdeveloper.org>
 * @package Frisk
 */
class HelperSuite extends Helper
{
	private static $suiteConfig = 'test.ini';
	private static $suiteSet = array();
	private static $suiteConfigKey = 'tests';
	
	/**
	 * Load the test-specific configuration and get the tests detail
	 */
	public function execute($suiteConfig = null)
	{		
		$configFilePath = HelperConfig::getConfigValue('tests_directory').'/'.self::$suiteConfig;
		try {
			HelperConfig::execute($configFilePath,self::$suiteConfigKey);
		}catch(Exception $e){
			throw new Exception($e->getMessage());
		}
	}

	/**
	 * Look in the "suites" value in the configuration settings for a name match
	 *
	 * @param string $suiteName Name of suite to match (exact)
	 * @return array $suiteConfig Configuration settings for the suite
	 */
	public function findSuiteConfigByName($suiteName)
	{
		$testSettings 	= HelperConfig::getConfigKeyValues(self::$suiteConfigKey);
		$suiteConfig 	= array();
		foreach($testSettings['suite'] as $settings){
			if($settings['name']==$suiteName){
				$suiteConfig = $settings;
			}
		}
		return $suiteConfig;
	}
}

?>
