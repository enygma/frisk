<?php

class ActionSetField extends Action
{
	public function __construct($arg,$optionalArgs=null)
	{
		//var_dump($optionalArgs);	
		$this->output=array('test_txt'=>'this is a test');
	}
}

?>