<?php
/**
 * Helper for working with test suites
 *
 * @author Chris Cornutt <ccornutt@phpdeveloper.org>
 * @package Frisk
 */
class HelperSuite extends Helper
{
	private static $suiteConfig = 'test.ini.dist';
	private static $suiteSet = array();
	
	/**
	 * Load the test-specific configuration and get the tests detail
	 */
	public function execute($suiteName)
	{		
		$configFilePath = HelperConfig::getConfigValue('tests_directory').'/'.self::$suiteConfig;
		HelperConfig::execute($configFilePath,'tests');
	}

	/**
	 * Look in the "suites" value in the configuration settings for a name match
	 *
	 * @param string $suiteName Name of suite to match (exact)
	 * @return array $suiteConfig Configuration settings for the suite
	 */
	public function findSuiteConfigByName($suiteName)
	{
		$testSettings 	= HelperConfig::getConfigKeyValues('tests');
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
