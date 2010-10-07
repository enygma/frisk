<?php

class AssertResponseCode extends Assert
{
	public function assertSetup(){}
	public function assertTeardown(){}
	
	public function assertExecute()
	{
		$httpCode 		= parent::$currentArguments[0];
		$httpResponse 	= parent::$currentHttp->getResponseCode();
		
		if($httpCode!=$httpResponse){
			throw new Exception(get_class().': No match on HTTP response code ('.$httpCode.')!');
		}
	}
}

?>