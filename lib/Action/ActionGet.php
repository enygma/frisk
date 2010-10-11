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
		$msgObj 	= &parent::getCurrentMessage();
		$http 		= $msgObj::getData('currentHttp');
		$arguments 	= $msgObj::getData('currentArguments');
		
		$this->getHost 		= $arguments[1];
		$this->getLocation 	= 'http://'.$this->getHost.'/'.$arguments[0];
		
		$msgObj=&parent::getCurrentMessage();
		$msgObj::setData('getLocation',$this->getLocation);
		$msgObj::setData('getHost',$this->getHost);
		
		$http = new HttpRequest($this->getLocation,HttpRequest::METH_GET);
		
		if($additionalHeaders=$msgObj::getData('httpHeaders')){
			$http->setHeaders($additionalHeaders['httpHeaders']);
		}
		
		try {
			$httpReturn = $http->send();
			$msgObj::setData('currentHttp',$httpReturn);
			return $httpReturn;
		}catch(Exception $e){
			throw new Exception(get_class().': '.$e->getMessage());
		}
	}
}

?>
