<?php

class ActionPost extends Action
{
	private $postLocation 	= '';
	private $postData		= array();
	private $postHost		= '';
	public $httpOutput 		= null;
	public $headers			= null;
	private $arguments		= null;
	
	/* action post */
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