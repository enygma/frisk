<?php

class ActionSetfield extends Action
{
	public function __construct($arg,$optionalArgs=null)
	{
		//var_dump($optionalArgs);	
		$this->output=array('test_txt'=>'this is a test');
	}
	public function execute(){
		
		// look at the page and be sure there's a form value named
		// with the parameter(s)
		
		$httpBody = parent::$currentHttp->getBody();
		
		$arr=array('test'=>'foo');

		HelperParseHtml::execute($httpBody);
		
		
		
	}
}

?>
