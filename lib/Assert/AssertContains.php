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
class AssertContains extends Assert
{
	public function assertSetup(){}
	public function assertTeardown(){}
	
	/**
	 * Evaluate that one value contains the other
	 * @return boolean
	 */
	public function assertExecute()
	{	
		$matchAgainst 	= parent::$currentHttp->getBody();
		$toFind 	= parent::$currentArguments[0];

		if(isset(parent::$currentArguments[1])){
			switch(parent::$currentArguments[1]){
				case TEST::TYPE_XPATH :
					$methodName='matchXpath';
					break;
				default:
					$methodName='matchDirect';
			}
		}
		$this->$methodName($matchAgainst,$toFind);
		return true;
	}
	private function matchDirect($matchValue1,$matchValue2){
		if(!stristr($matchAgainst,$toFind)){
                        throw new Exception(get_class().': Term not found');
                }
	}
	private function matchXpath($xpathExpression,$matchValue){
		throw new Exception('XPath search not yet enabled');
	}
}

?>
