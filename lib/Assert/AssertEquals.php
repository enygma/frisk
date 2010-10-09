<?php
/**
 * Assertion Class - Assert that one value is equal to another
 *
 * @author Chris Cornutt <ccornutt@phpdeveloper.org>
 * @package Frisk
 */
class AssertEquals extends Assert 
{
	public function assertSetup(){}
	public function assertTeardown(){}

	/**
	 * Main execution method - Evaluate that the two values given are equal
	 *
	 * @return boolean
	 * @throws Exception
	 */
	public function assertExecute()
	{	
		$msgObj 	= &parent::getCurrentMessage();
		$http 		= $msgObj::getData('currentHttp');
		$arguments 	= $msgObj::getData('currentArguments');
		$compareTo	= $arguments[0];
		
		if(!isset($arguments[1])){
			// If we're not given two terms, match against the body of the latest httpRequest
			$compareAgainst = $http->getBody();
		}else{
			$compareAgainst = $arguments[1];
		}
		
		if(trim($compareAgainst)!=trim($compareTo)){
			throw new Exception(get_class().': Values not equal!');
		}
		return true;
	}

}

?>
