<?php

/**
 * Action to fetch a remote resource via GET
 *
 * @author Chris Cornutt <ccornutt@phpdeveloper.org>
 * @package Frisk
 *
 */
class ActionGet extends Action
{
	/**
	 * The location of the remote resource to fetch
	 * @var string
	 */
	private $getLocation 	 = '';

	/**
	 * Hostname to make request to
	 * @var string
	 */
	private $getHost	 = '';
	
	/**
	 * Main execution method - make GET request
	 *
	 * @return object $http HttpMessage resource
	 * @throws Exception
	 */
	public function execute()
	{
		$this->getHost 		= parent::$currentArguments[1];
		$this->getLocation 	= 'http://'.$this->getHost.'/'.parent::$currentArguments[0];

		parent::setParentArgument('getLocation',$this->getLocation);
		
		$http = new HttpRequest($this->getLocation,HttpRequest::METH_GET);
		try {
			return $http->send();
		}catch(Exception $e){
			throw new Exception(get_class().': '.$e->getMessage());
		}
	}
}

?>
