<?php

class AssertCookieIsSet extends Assert
{
	public function assertSetup(){}
	public function assertTeardown(){}
	
	public function assertExecute()
	{
		$httpHeaders	= parent::$currentHttp->getHeaders();
		$httpCookieName = parent::$currentArguments[0];
		$httpCookies	= $httpHeaders['Set-Cookie'];
		
		if(count(array_walk($httpCookies,function(&$value,$key,$cookieName){
			if(!isset($value->cookies[$cookieName])){ unset($value); }
		},$httpCookieName))==0){
			throw new Exception(get_class().': Cookie not found!');
		}
	}
}

?>