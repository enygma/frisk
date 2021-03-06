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
		$settings	= array();
		
		//if the first parameter is an array, they might be defining lots of things - parse!
		if(is_array($arguments[0])){
			// be sure that at least the location and host are set
			if(!isset($arguments[0]['host']) || !isset($arguments[0]['location']) ){
				throw new Exception('Missing required arguments!');
			}
			$settings = $arguments[0];
		}else{
			// if we're just using the normal arguments - map them
			$settingKeys = array('location','host','requestData','outputFormat');
			foreach($arguments as $argumentIndex => $argument){
				$argumentKey = $settingKeys[$argumentIndex];
				$settings[$argumentKey]=($arguments[$argumentIndex]) ? $arguments[$argumentIndex] : null;
			}
		}
		
		// Be sure we at least have the location
		if(!$settings['location'] || gettype($settings['location'])!='string'){
			throw new Exception(get_class().' Invalid post location!');
		}
		if(!isset($settings['host']) || gettype($settings['host'])!='string'){
			throw new Exception('Action Post: Invalid hostname!');
		}
		$this->postHost		= $settings['host'];

		$this->postLocation	= (strpos($settings['location'],'http://')===false) ? 'http://'.$this->postHost.$settings['location'] : $settings['location'];
		
		$this->postData		= (isset($settings['requestData'])) ? $settings['requestData'] : '';
		
		$msgObj=&parent::getCurrentMessage();
		$msgObj::setData('postLocation',$this->postLocation);
		$msgObj::setData('postHost',$this->postHost);
		$msgObj::setData('outputFormat',(isset($settings['outputFormat'])) ? $settings['outputFormat'] : 'txt');
		
		$http = new HttpRequest($this->postLocation,HttpRequest::METH_POST);
		if(is_array($this->postData)){
			$http->setPostFields($this->postData);
		}elseif($this->postData instanceof SimpleXMLElement){
			// we're going to convert this to a literal XML string
			$this->postData = $this->postData->asXML();
			$http->setBody($this->postData);
		}else{
			$http->setBody($this->postData);
		}
		
		if(isset($settings['headers']) && count($settings['headers'])>0){
			$http->setHeaders($settings['headers']);
		}
		
		if($additionalHeaders=$msgObj::getData('httpHeaders')){
			$http->setHeaders($additionalHeaders['httpHeaders']);
		}
		
		try {
			try {
				$httpReturn = $http->send();
				
				// Check for a redirect so we can follow...
				$responseCode = $http->getResponseCode();
				if($responseCode==301 && isset($arguments[0]['followRedirects']) && $arguments[0]['followRedirects']==true){
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
		}catch(HttpException $e){
			throw new Exception(get_class().' '.$e->getMessage());
		}
	}
}

?>
