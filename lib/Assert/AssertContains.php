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
		
		$matchAgainst	= ($matchAgainst!=null) ? $matchAgainst : (($http) ? $http->getBody() : '');
		$toFind 	= $arguments[0];

		// mutiple inut formats (json, txt, xml)
		try {
			switch(strtolower($outputFormat)){
				case 'json': 
					// use the json searching with a FilterIterator
					if(isset($arguments[1])){
						$this->matchJsonFilterExact($toFind,$matchAgainst,$arguments[1]);
					}else{
						$this->matchJsonFilter($toFind,$matchAgainst);
					}
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

	/**
	 * Match the strings exactly
	 *
	 * @param string $matchValue1 String one
	 * @param string $matchValue2 Strine two
	 *
	 * @throws Exception
	 * @return void
	 */
	private function matchDirect($matchValue1,$matchValue2)
	{
		if(!stristr($matchValue1,$matchValue2)){
			throw new Exception(get_class().': Term not found');
		}
	}

	/**
	 * Match against a string with an XPath expression
	 *
	 * @param string $matchValue String to match against
	 * @param string $xpathExpression XPath expression
	 * 
	 * @throws Exception
	 * @return void
	 */
	private function matchXpath($matchValue,$xpathExpression)
	{
		//throw new Exception('XPath search not yet enabled');
		$xml		= HelperForm::parseHtml($matchValue);
		$matches	= $xml->xpath($xpathExpression);
		
		if(count($matches)<=0){
			throw new Exception(get_class().': Pattern not matched');
		}
	}

	/**
	 * Match against the contents of a JSON message.
	 * Matching is a "if found anywhere..." type
	 *
	 * @param string $toFind String to find in the value of a element in JSON
	 * @param string $jsonMessage JSON message returned from POST/GET request
	 *
	 * @throws Exception
	 * @return void
	 */
	private function matchJsonFilter($toFind,$jsonMessage)
	{
		// Use our FilterIterator
		$found = false;
		$jsonMessage = get_object_vars(json_decode($jsonMessage));
		if ($jsonMessage != null) {
			$filterResult = new FilterJsonFind(
				new RecursiveIteratorIterator(new RecursiveArrayIterator($jsonMessage)),
				$toFind
			);
			foreach($filterResult as $resultKey => $resultValue){
				if($resultValue==$toFind){ $found = true; }
			}			
		}
		if(!$found){
			throw new Exception(get_class().': Term not found in object');
		}
	}

	/**
	 * Match against a JSON element exactly 
	 * if something in location matches term exactly
	 *
	 * @param string $toFind String to look for
	 * @param string $jsonMessage JSON message (text) returned from GET/POST
	 * @param string $findLocation String representation of the location to search on
	 *
	 * @throws Exception
	 * @return void
	 */
	private function matchJsonFilterExact($toFind,$jsonMessage,$findLocation)
	{
		// take our message and make json out of it
		$jsonMessage = get_object_vars(json_decode($jsonMessage));
		$searchPath  = explode('/',$findLocation);
		$results     = array();

		if($jsonMessage != null && isset($jsonMessage[$searchPath[0]])){

			$filterResult = new FilterJsonFindExact(
				new RecursiveIteratorIterator(new RecursiveArrayIterator($jsonMessage)),
				$toFind,
				array_pop($searchPath)
			);
			foreach($filterResult as $k => $result){ 
				$results[$k]=$result;
			}

			if(count($results)==0){
				throw new Exception(get_class().': Object match not found!');
			}
		}else{
			throw new Exception(get_class().': Base element not found!');
		}
	}
}

?>
