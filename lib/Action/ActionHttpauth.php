<?php

/**
 * Action class to set the Http-Auth headers for a post request
 *
 * @author Chris Cornutt <ccornutt@phpdeveloper.org>
 * @package Frisk
 *
 */
class ActionHttpauth extends Action
{	

	/**
	 * Main execute method - Set additional header for Http-Auth
	 * 
	 * @return $http object HttpResponse object
	 * @throws Exception
	 */
	public function execute()
	{	
		$msgObj 	= &parent::getCurrentMessage();
		$http 		= $msgObj::getData('currentHttp');
		$arguments 	= $msgObj::getData('currentArguments');
		
		//set the header information needed for requests
		//future requests should look for these headers
		$auth		= 'Basic '.base64_encode($arguments[0].':'.$arguments[1]);
		
		$value=array('Authorization'=>$auth);
		$msgObj::setData('httpHeaders',$value,false);
	
	}
}

?>