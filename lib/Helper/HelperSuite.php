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

		$suiteSettings = HelperConfig::getConfigKeyValues('tests');
	}
	
}

?>