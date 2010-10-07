<?php
/* Assert that value is equal to another given */

class AssertEquals extends Assert 
{
	public function assertSetup(){}
	public function assertTeardown(){}
	/**
	 * Evaluate that the two values given are equal
	 */
	public function assertExecute()
	{	
		$compareTo 		= parent::$currentArguments[0];
		
		if(!isset(parent::$currentArguments[1])){
			// If we're not given two terms, match against the body of the latest httpRequest
			$compareAgainst = parent::$currentHttp->getBody();
		}else{
			$compareAgainst = parent::$currentArguments[1];
		}
		
		if(trim($compareAgainst)!=trim($compareTo)){
			throw new Exception(get_class().': Values not equal!');
		}
	}

}

?>
