<?php

class ActionGet extends Action
{
	public $output 		 = null;
	private $getLocation = '';
	private $getHost	 = '';
	
	public function __construct($args){
		$this->getHost 		= $args[1];
		$this->getLocation 	= 'http://'.$this->getHost.'/'.$args[0];
		
		$http = new HttpRequest($this->getLocation,HttpRequest::METH_GET);
		try {
			$this->output=$http->send()->getBody();
		}catch(Exception $e){
			throw new Exception(get_class().' '.$e->getMessage());
		}
	}
}

?>