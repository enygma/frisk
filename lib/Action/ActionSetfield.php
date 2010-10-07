<?php

class ActionSetfield extends Action
{
	public function execute()
	{
		
		// look at the page and be sure there's a form value named
		// with the parameter(s)
		
		$httpBody = parent::$currentHttp->getBody();

		$parameters=array(
			'fieldName'	=> 'logo',
			'httpBody'	=> $httpBody
		);

		HelperForm::execute($parameters);
		
		
		
	}
}

?>
