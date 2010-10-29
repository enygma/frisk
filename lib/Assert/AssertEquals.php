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
		$msgObj 		= &parent::getCurrentMessage();
		$http 			= $msgObj::getData('currentHttp');
		$arguments 		= $msgObj::getData('currentArguments');
		$outputFormat	= $msgObj::getData('outputFormat');
		$compareTo		= $arguments[0];
		
		if(!isset($arguments[1])){
			// If we're not given two terms, match against the body of the latest httpRequest
			$compareAgainst = ($http) ? $http->getBody() : '';
		}else{
			$compareAgainst = $arguments[1];
		}

		$passTest = true;
		switch(strtolower($outputFormat)){
			case 'json':
				if(json_encode($compareTo)!=json_encode($compareAgainst)){
					$passTest = false;
				}
				break;
			default:
				// match as strings
				if(trim($compareAgainst)!=trim($compareTo)){
					$passTest = false;
				}
		}
		
		if(!$passTest){
			throw new Exception(get_class().': Values not equal!');
		}
		return true;
	}

}

?>
