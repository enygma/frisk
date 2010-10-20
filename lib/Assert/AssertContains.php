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
		$msgObj 	= &parent::getCurrentMessage();
		$http 		= $msgObj::getData('currentHttp');	
		$arguments 	= $msgObj::getData('currentArguments');
		$matchAgainst	= $msgObj::getData('matchAgainst');
		$outputFormat	= $msgObj::getData('outputFormat');
		
		$matchAgainst	= ($matchAgainst!=null) ? $matchAgainst : $http->getBody();
		$toFind 	= $arguments[0];

		// mutiple inut formats (json, txt, xml)
		try {
			switch(strtolower($outputFormat)){
				case 'json': 
					// use the json searching with a FilterIterator
					$this->matchJsonFilter($toFind,$matchAgainst);
					break;
				case 'xml': 
					// use the xml searching
					break;
				default:
					// treat is as plain text
					if(isset($arguments[1])){
						switch($arguments[1]){
						case TEST::TYPE_XPATH :
							$methodName='matchXpath';
							break;
						default:
							$methodName='matchDirect';
						}
					}else{ $methodName='matchDirect'; }
					$this->$methodName($matchAgainst,$toFind);
			}
		}catch(Exception $e){
			throw new Exception($e->getMessage());
		}
		return true;
	}
	private function matchDirect($matchValue1,$matchValue2){
		if(!stristr($matchValue1,$matchValue2)){
			throw new Exception(get_class().': Term not found');
		}
	}
	private function matchXpath($matchValue,$xpathExpression){
		//throw new Exception('XPath search not yet enabled');
		$xml		= HelperForm::parseHtml($matchValue);
		$matches	= $xml->xpath($xpathExpression);
		
		if(count($matches)<=0){
			throw new Exception(get_class().': Pattern not matched');
		}
	}
	private function matchJsonFilter($toFind,$jsonMessage)
	{
		// Use our FilterIterator
		$found = false;
		$jsonMessage = get_object_vars(json_decode($jsonMessage));
		if ($jsonMessage != null) {
			$filterResult = new FilterJsonFind(
				new RecursiveIteratorIterator(new RecursiveArrayIterator($jsonMessage))
			);
			foreach($filterResult as $resultKey => $resultValue){
				if($resultValue==$toFind){ $found = true; }
			}			
		}
		if(!$found){
			throw new Exception(get_class().': Term not found in object');
		}
	}
}

?>
