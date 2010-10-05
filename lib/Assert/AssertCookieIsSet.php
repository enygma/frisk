<?php

class AssertCookieIsSet extends Assert
{
	public function assertSetup(){}
	public function assertTeardown(){}
	
	public function assertExecute()
	{
		$httpCookieName = $this->assertArguments[0];
		$httpCookies	= $this->input['httpObject']->getResponseCookies();
		
		if(count(array_walk($httpCookies,function(&$value,$key,$cookieName){
			if(!isset($value->cookies[$cookieName])){ unset($value); }
		},$httpCookieName))==0){
			throw new Exception(get_class().': Cookie not found!');
		}
	}
}

?>