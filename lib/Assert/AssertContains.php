<?php

class AssertContains extends Assert
{
	public function assertSetup(){}
	public function assertTeardown(){}
	
	/**
	 * Evaluate that one value contains the other
	 */
	public function assertExecute()
	{	
		$matchAgainst 	= parent::$currentHttp->getBody();
		$toFind 		= parent::$currentArguments[0];
		
		// check to see if it's there!
		if(!stristr($matchAgainst,$toFind)){
			throw new Exception(get_class().': Term not found');
		}
	}
}

?>