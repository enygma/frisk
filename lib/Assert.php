<?php
/* Abstract assertion class */

abstract class Assert
{
	abstract protected function assertExecute();
	abstract protected function assertSetup();
	abstract protected function assertTeardown();
	
	public $assertArguments = array();
	
	public function __construct($args){
		$this->assertArguments=$args;
	}
}

?>
