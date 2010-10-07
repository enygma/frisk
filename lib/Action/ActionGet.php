<?php

class ActionGet extends Action
{
	public $output 		 = null;
	public $headers		 = null;
	private $getLocation 	 = '';
	private $getHost	 = '';
	
	public function execute()
	{
		$this->getHost 		= parent::$currentArguments[1];
		$this->getLocation 	= 'http://'.$this->getHost.'/'.parent::$currentArguments[0];
		
		$http = new HttpRequest($this->getLocation,HttpRequest::METH_GET);
		try {
			return $http->send();
		}catch(Exception $e){
			throw new Exception(get_class().': '.$e->getMessage());
		}
	}
}

?>
