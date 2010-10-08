<?php

class ActionSubmitform extends Action
{
	public function execute(){
		
		$httpBody = parent::$currentHttp->getBody();
		$parameters=array('httpBody'=>$httpBody);
		HelperForm::execute($parameters);
		
		$httpRequest 	= parent::$currentHttp;
		$arguments		= parent::$currentArguments;
		
		$httpRequest->setType(HTTP_MSG_REQUEST);
		
		var_dump($arguments);
		
		$post = new ActionPost($httpRequest,$arguments);
		$http=$post->execute();
		var_dump($http);
	}
}

?>
