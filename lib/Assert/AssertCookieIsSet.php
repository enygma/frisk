<?php
/**
 * Assertion Class : Check to ensure that a cookie is set in the response 
 * from the remote resource. 
 *
 * @author Chris Cornutt <ccornut@phpdeveloper.org>
 * @package Frisk
 *
 */
class AssertCookieIsSet extends Assert
{
	public function assertSetup(){}
	public function assertTeardown(){}
	
	/**
	 * Main execution function - Gets cookie/header information
	 * from the request to see if the key exists
	 *
	 * @return boolean
	 * @throws Exception
	 */
	public function assertExecute()
	{
		$msgObj 	= &parent::getCurrentMessage();
		$http 		= $msgObj::getData('currentHttp');
		$arguments 	= $msgObj::getData('currentArguments');
		
		$httpHeaders	= $http->getHeaders();
		$httpCookieName = $arguments[0];
		$httpCookies	= $httpHeaders['Set-Cookie'];
		
		if(count(array_walk($httpCookies,function(&$value,$key,$cookieName){
			if(!isset($value->cookies[$cookieName])){ unset($value); }
		},$httpCookieName))==0){
			throw new Exception(get_class().': Cookie not found!');
		}
		return true;
	}
}

?>
