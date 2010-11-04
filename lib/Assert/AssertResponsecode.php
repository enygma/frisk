<?php
/**
 * Assertion Class - Check to see if the response code matches the input
 *
 * @author Chris Cornutt <ccornutt@phpdeveloper.org>
 * @package Frisk
 *
 */
class AssertResponsecode extends Assert
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
		$msgObj 	= &parent::getCurrentMessage();
		$http 		= $msgObj::getData('currentHttp');
		$arguments 	= $msgObj::getData('currentArguments');
		
		$httpCode 		= $arguments[0];
		$httpResponse 	= ($http) ? $http->getResponseCode() : '0';
		
		if($httpCode!=$httpResponse){
			throw new Exception(get_class().': No match on HTTP response code ('.$httpCode.')!');
		}
		return true;
	}
}

?>
