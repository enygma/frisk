<?php

/**
 * Action class to make a POST request to a remote resource
 *
 * @author Chris Cornutt <ccornutt@phpdeveloper.org>
 * @package Frisk
 *
 */
class ActionPost extends Action
{	
	/**
	 * Remote location to issue POST request to
	 * @var string
	 */
	private $postLocation 	= '';

	/**
	 * Container for post data (input from call)
	 * @var array
	 */
	private $postData	= array();

	/**
	 * Hostname to issue POST request to
	 * @var string
	 */
	private $postHost	= '';

	/**
	 * Main execute method - Make POST request to remote resource
	 * 
	 * @return $http object HttpResponse object
	 * @throws Exception
	 */
	public function execute()
	{	
		$msgObj 	= &parent::getCurrentMessage();
		$http 		= $msgObj::getData('currentHttp');
		$arguments 	= $msgObj::getData('currentArguments');
		
		// Be sure we at least have the location
		if(!$arguments[0] || gettype($arguments[0])!='string'){
			throw new Exception(get_class().' Invalid post location!');
		}
		if(!isset($arguments[1]) || gettype($arguments[0])!='string'){
			throw new Exception('Action Post: Invalid hostname!');
		}
		$this->postHost		= $arguments[1];
		$this->postLocation	= 'http://'.$this->postHost.$arguments[0];
		$this->postData		= (isset($arguments[2])) ? $arguments[2] : '';
		
		$msgObj=&parent::getCurrentMessage();
		$msgObj::setData('postLocation',$this->postLocation);
		$msgObj::setData('postHost',$this->postHost);
		
		$http = new HttpRequest($this->postLocation,HttpRequest::METH_POST);
		$http->setPostFields($this->postData);
		
		try {
			$httpReturn = $http->send();
			$msgObj::setData('currentHttp',$httpReturn);
			return $httpReturn;
		}catch(HttpException $e){
			throw new Exception(get_class().' '.$e->getMessage());
		}
	}
}

?>
