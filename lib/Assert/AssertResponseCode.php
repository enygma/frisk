<?php

class AssertResponseCode extends Assert
{
	public function assertSetup(){}
	public function assertTeardown(){}
	
	public function assertExecute()
	{
		$httpCode 		= $this->assertArguments[0];
		$httpResponse 	= $this->input['httpResponseCode'];
		
		if($httpCode!=$httpResponse){
			throw new Exception(get_class().': No match on HTTP response code!');
		}
	}
}

?>