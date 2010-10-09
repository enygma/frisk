<?php
/* Base test class */
class Test
{
	/**
	 * Store the test status
	 */
	public $testStatus 	= array();
	public $optionalArguments;
	private $currentHttp 	= null;
	static $currentMessage = null;

	const TYPE_XPATH 	= 1;
	
	public static function setCurrentMessage(&$msgObj)
	{
		self::$currentMessage=$msgObj;
	}
	public static function getCurrentMessage()
	{
		return self::$currentMessage;
	}
	
	public function __call($name,$arg)
	{	
		// going back up, our original test name is in $backtrace[2]
		$backtrace	= debug_backtrace();
		$testName	= $backtrace[2]['function'];
		$negate		= false;
		
		// if the first part is "assert, call the assert.
		// otherwise, try an action
		$path = __DIR__.'/'.((stristr($name,'assert')) ? 'lib/Assert' : 'lib/Action');
		$currentMessage=&self::getCurrentMessage();
		$currentMessage::setData('currentArguments',$arg);
		
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
				$obj->optionalArguments=$this->optionalArguments;
				
				$obj::setCurrentMessage($currentMessage);
				
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
		}elseif(stristr($name,'marktest')){
			preg_match('/marktest(.*)?/i',$name,$match);
			$this->testStatus[$testName]=array(null,ucwords($match[1].': '.$arg[0]));
		}else{
			// assume it's an action
			$actionName='Action'.ucwords(strtolower($name));
			try {
				$actionObj = new $actionName($this->currentHttp,$arg);
		
				$actionObj::setCurrentMessage($currentMessage);

				// If we have optional arguments from before, merge
				$opt=(count($actionObj::$optionalArguments)>0) ? $actionObj::$optionalArguments : array();
				$actionObj::$optionalArguments=array_merge(get_defined_vars($this),$opt);
				$this->optionalArguments=$actionObj::$optionalArguments;
		
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
