<?php

class ActionSetfield extends Action
{
	public function execute()
	{
		
		// look at the page and be sure there's a form value named
		// with the parameter(s)
		
		$httpBody = parent::$currentHttp->getBody();

		$parameters=array('httpBody'=>$httpBody);

		HelperForm::execute($parameters);
		if(HelperForm::isFormFieldByName('q')){
			echo 'match';
			
			$data=array('q'=>'php');
			//HelperForm::setFormData($data);
			
			
		}else{ echo 'no match'; }
		
		
	}
}

?>
