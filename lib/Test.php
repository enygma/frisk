<?php
/* Base test class */
class Test
{
	/**
	 * Store the test status
	 */
	public $testStatus 	= array();
	private $currentHttp 	= null;

	const TYPE_XPATH 	= 1;
	
	public function __call($name,$arg)
	{
		// going back up, our original test name is in $backtrace[2]
		$backtrace	= debug_backtrace();
		$testName	= $backtrace[2]['function'];
		$negate		= false;
		
		// if the first part is "assert, call the assert.
		// otherwise, try an action
		$path = __DIR__.'/'.((stristr($name,'assert')) ? 'lib/Assert' : 'lib/Action');
		
		if(stristr($name,'assert')){
			// it's an assertion!
			preg_match('/assert(.*)?/i',$name,$match);

			//check for "not"
			if(strtolower(substr($match[1],0,3))=='not'){
				$match[1]=str_replace('not','',strtolower($match[1]));
				$negate=true;
			}

			$assertName = 'Assert'.ucwords(strtolower($match[1]));
			try{
				$obj = new $assertName($this->currentHttp,$arg);
				$obj->input		= (isset($this->output)) ? $this->output : null;
				$obj->assertSetup();
				$obj->assertExecute();
				$obj->assertTeardown();
				
				// pass!
				$this->testStatus[$testName]=array('pass','');
			}catch(Exception $e){
				$this->testStatus[$testName]=array('fail',$e->getMessage());
			}
			// If we're negating, set the flag back over
			if($negate){
				$this->testStatus[$testName][0]=($this->testStatus[$testName][0]=='pass') ? 'fail' : 'pass';
			}
			return $this;
		}else{
			// assume it's an action
			$actionName='Action'.ucwords(strtolower($name));
			try {
				$actionObj = new $actionName($this->currentHttp,$arg);

				// If we have optional arguments from before, merge
				$opt=(count($actionObj::$optionalArguments)>0) ? $actionObj::$optionalArguments : array();
				$actionObj::$optionalArguments=array_merge(get_defined_vars($this),$opt);
				
				$returnObj = $actionObj->execute();

				if($returnObj instanceof HttpMessage){
					$this->currentHttp=$returnObj;
				}
				
				// return thyself!
				return $this;
			}catch(Exception $e){
				echo 'error: '.$e->getMessage();
			}
		}	
	}
}

?>
