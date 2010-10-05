<?php
/* Base test class */
class Test
{
	/**
	 * Store the test status
	 */
	public $testStatus = array();
	
	public function __call($name,$arg)
	{
		// going back up, our original test name is in $backtrace[2]
		$backtrace	= debug_backtrace();
		$testName	= $backtrace[2]['function'];
		
		// if the first part is "assert, call the assert.
		// otherwise, try an action
		$path = __DIR__.'/'.((stristr($name,'assert')) ? 'lib/Assert' : 'lib/Action');
		
		if(stristr($name,'assert')){
			// it's an assertion!
			preg_match('/assert(.*)?/i',$name,$match);
			$assertName = 'Assert'.ucwords(strtolower($match[1]));
			try{
				$obj = new $assertName($arg);
				$obj->input		= (isset($this->output)) ? $this->output : null;
				$obj->assertSetup();
				$obj->assertExecute();
				$obj->assertTeardown();
				
				// pass!
				$this->testStatus[$testName]=array('pass','');
			}catch(Exception $e){
				$this->testStatus[$testName]=array('fail',$e->getMessage());
			}
		}else{
			// assume it's an action
			$actionName='Action'.ucwords(strtolower($name));
			try {
				$optionalArgs	= get_defined_vars($this);
				$obj 			= new $actionName($arg,$optionalArgs);
				$this->output	= $obj->output;
				return $this;
			}catch(Exception $e){
				echo 'error: '.$e->getMessage();
			}
		}	
	}
}

?>