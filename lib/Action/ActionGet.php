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
		
		//if the first parameter is an array, they might be defining lots of things - parse!
		if(is_array($arguments[0])){
			// be sure that at least the location and host are set
			if(!isset($arguments[0]['host']) || !isset($arguments[0]['location']) ){
				throw new Exception('Missing required arguments!');
			}
			$settings = $arguments[0];
		}else{
			// if we're just using the normal arguments - map them
			$settingKeys = array('location','host','outputFormat');
			foreach($arguments as $argumentIndex => $argument){
				$argumentKey = $settingKeys[$argumentIndex];
				$settings[$argumentKey]=($arguments[$argumentIndex]) ? $arguments[$argumentIndex] : null;
			}
		}
		
		$this->getHost 		= $settings['host'];
		$this->postLocation	= (strpos($settings['location'],'http://')===false) ? 'http://'.$this->postHost.$settings['location'] : $settings['location'];
		
		// see if we need to append anything
		if(isset($settings['requestData'])){
			$requestString='';
			foreach($settings['requestData'] as $requestKey => $requestValue){
				$requestString.=urlencode($requestKey).'='.urlencode($requestValue).'&';
			}
			$this->getLocation.=(strpos($this->getLocation,'?')!=false) ? '&'.$requestString : '?'.$requestString;
		}
		
		$msgObj=&parent::getCurrentMessage();
		$msgObj::setData('getLocation',$this->getLocation);
		$msgObj::setData('getHost',$this->getHost);
		$msgObj::setData('outputFormat',(isset($settings['outputFormat'])) ? $settings['outputFormat'] : 'txt');
		
		$http = new HttpRequest($this->getLocation,HttpRequest::METH_GET);
		
		if($additionalHeaders=$msgObj::getData('httpHeaders')){
			$http->setHeaders($additionalHeaders['httpHeaders']);
		}
		
		try {
			try {
				$httpReturn = $http->send();
				
				// Check for a redirect so we can follow...
				$responseCode = $http->getResponseCode();
				if($responseCode==301 && isset($arguments[0]['follow_redirects']) && $arguments[0]['follow_redirects']==true){
					$header 					= $httpReturn->getHeaders();
					$arguments[0]['location']	= $header['Location'];
					$msgObj::setData('currentArguments',$arguments);
					return ActionPost::execute();
				}
				
			}catch(Exception $e){
				throw new Exception($e->getMessage());
				return false;
			}
			HelperSession::execute($httpReturn->getHeaders());

			$msgObj::setData('__lastRequest',$http->getRawRequestMessage());
			$msgObj::setData('__lastResponse',$http->getRawResponseMessage());

			$msgObj::setData('currentHttp',$httpReturn);
			return $httpReturn;
		}catch(Exception $e){
			throw new Exception(get_class().': '.$e->getMessage());
		}
	}
}

?>
