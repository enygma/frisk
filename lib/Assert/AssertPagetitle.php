<?php
/**
 * Assertion Class - Check page title for an exact match
 *
 * @author Chris Cornutt <ccornutt@phpdeveloper.org>
 * @package Frisk
 *
 */
class AssertPagetitle extends Assert
{
	public function assertSetup(){}
	public function assertTeardown(){}
	
	/**
	 * Main execution method - Grab the response HTML and check the
	 * title element to validate against
	 *
	 * @return boolean
	 * @throws Exception
	 */
	public function assertExecute()
	{
		$msgObj 	= &parent::getCurrentMessage();
		$http 		= $msgObj::getData('currentHttp');
		$arguments 	= $msgObj::getData('currentArguments');
		
		// match to the page title
		$xml=HelperForm::parseHTML($http->getBody());
		$matches=$xml->xpath("//*[name()='title']");
		
		if(!isset($matches[0]) || (string)$matches[0]!=$arguments[0]){
			throw new Exception(get_class().': HTML title not matched!');
		}
	}
	
}
?>