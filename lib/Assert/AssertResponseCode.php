<?php
/**
 * Assertion Class - Check to see if the response code matches the input
 *
 * @authro Chris Cornutt <ccornutt@phpdeveloper.org>
 * @package Frisk
 *
 */
class AssertResponseCode extends Assert
{
	public function assertSetup(){}
	public function assertTeardown(){}
	
	/**
	 * Main execution method - Grab response code from current HTTP connection
	 * and check for equality
	 *
	 * @return boolean
	 * @throws Exception
	 */
	public function assertExecute()
	{
		$httpCode 		= parent::$currentArguments[0];
		$httpResponse 	= parent::$currentHttp->getResponseCode();
		
		if($httpCode!=$httpResponse){
			throw new Exception(get_class().': No match on HTTP response code ('.$httpCode.')!');
		}
		return true;
	}
}

?>
