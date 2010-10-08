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
		// Be sure we at least have the location
		if(!parent::$currentArguments[0] || gettype(parent::$currentArguments[0])!='string'){
			throw new Exception(get_class().' Invalid post location!');
		}
		if(!isset(parent::$currentArguments[1]) || gettype(parent::$currentArguments[0])!='string'){
			throw new Exception('Action Post: Invalid hostname!');
		}
		$this->postHost		= parent::$currentArguments[1];
		$this->postLocation	= 'http://'.$this->postHost.parent::$currentArguments[0];
		$this->postData		= (isset(parent::$currentArguments[2])) ? parent::$currentArguments[2] : '';
		
		$http = new HttpRequest($this->postLocation,HttpRequest::METH_POST);
		$http->setPostFields($this->postData);
		
		try {
			return $http->send();
		}catch(HttpException $e){
			throw new Exception(get_class().' '.$e->getMessage());
		}
	}
}

?>
