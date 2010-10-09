<?php
/**
 * Assertion Class: Checks to see if one value contains the other
 *
 * Takes in one paramater - the string to match
 * Target is incoming from the current HttpRequest
 *
 * @author Chris Cornutt <ccornutt@phpdeveloper.org>
 * @package Frisk
 */
class AssertRequestparameter extends Assert
{
	public function assertSetup(){}
	public function assertTeardown(){}
	
	/**
	 * Evaluate that the request parameter is in the return location/original location
	 * @return boolean
	 */
	public function assertExecute()
	{	
		$msgObj 	= &parent::getCurrentMessage();
		$http 		= $msgObj::getData('currentHttp');
		$arguments 	= $msgObj::getData('currentArguments');
		
		//var_dump($this->optionalArguments);
		
		$urlParts = parse_url($this->optionalArguments['arg'][0]);
		var_dump($urlParts);
		
		return true;
	}
	
}

?>