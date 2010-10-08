<?php

/**
 * Action to simulate the posting of form data to 
 * a remote script. Parameters some from submitForm() call
 * 
 * @author Chris Cornutt <ccornutt@phpdeveloper.org>
 * @package Frisk
 *
 */
class ActionSubmitform extends Action
{
	/**
	 * Main Action execution
	 *
	 * @return object HttpMessage Response object from HttpRequest
	 * @throws Exception
	 */
	public function execute(){
		
		// Validate that the fields exist on the form
		$httpBody = parent::$currentHttp->getBody();
		$parameters=array('httpBody'=>$httpBody);
		HelperForm::execute($parameters);

		$httpRequest 	= parent::$currentHttp;
		$arguments	= parent::$currentArguments;
		$urlParts 	= parse_url(parent::$optionalArguments['getLocation']);

		// Ensure that there's fields for the values we want to post
		foreach($arguments[0] as $fieldName=>$fieldValue){
			if(!HelperForm::isFormField($fieldName)){
				throw new Exception(get_class().': Form field "'.$fieldName.'" not found on remote resource');
			}
		}	

		$arguments=array(
			$urlParts['path'],
			$urlParts['host'],
			$arguments
		);
		
		$post = new ActionPost($httpRequest,$arguments);
		$http=$post->execute();
	
		vaR_dump($http->getBody());

		if($http->getResponseCode()!=200){
			throw new Exception(get_class().': Posting Unsuccessful ('.$http->getResponseCode().')');
		}
		return $http;
	}
}

?>
