<?php

class ActionPost extends Action
{
	private $postLocation 	= '';
	private $postData		= array();
	private $postHost		= '';
	public $httpOutput 			= null;
	public $headers			= null;
	
	/* action post */
	public function __construct($arg)
	{
		// Be sure we at least have the location
		if(!$arg[0] || gettype($arg[0])!='string'){
			throw new Exception(get_class().' Invalid post location!');
		}
		if(!isset($arg[1]) || gettype($arg[0])!='string'){
			throw new Exception('Action Post: Invalid hostname!');
		}
		$this->postHost		= $arg[1];
		$this->postLocation	= 'http://'.$this->postHost.$arg[0];
		$this->postData		= (isset($arg[2])) ? $arg[2] : '';
		
		$http = new HttpRequest($this->postLocation,HttpRequest::METH_POST);
		$http->setPostFields($this->postData);
		
		try {
			$this->output=array(
				'httpObject'		=> $http,
				'httpResponseObject'=> $http->send(),
				'httpOutput'		=> $http->getResponseBody(),
				'httpResponseCode'	=> $http->getResponseCode(),
				'httpHeaders'		=> $http->getResponseHeader()
			);
		}catch(HttpException $e){
			throw new Exception(get_class().' '.$e->getMessage());
		}
	}
}

?>