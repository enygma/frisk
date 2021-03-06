<?php
/* Base test class */
class Test
{
	/**
	 * Store the test status
	 */
	public $testStatus 	= array();
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
		if(!isset($this->testStatus[$testName])){
			$this->testStatus[$testName]=array();
		}
		
		// if the first part is "assert, call the assert.
		// otherwise, try an action
		$path = __DIR__.'/'.((stristr($name,'assert')) ? 'lib/Assert' : 'lib/Action');
		$currentMessage=&self::getCurrentMessage();
		$currentMessage::setData('currentArguments',$arg);
		
		// see if the test is skipped or incomplete
		foreach(array_keys($this->testStatus[$testName]) as $testKey){
			if(preg_match('/markTest(.*?)/',$testKey)){ 
				return $this; 
			}
		}

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
				$obj = new $assertName(null,$arg);
				$obj::setCurrentMessage($currentMessage);
				
				$obj->assertSetup();
				$obj->assertExecute();
				$obj->assertTeardown();
				
				// pass!
				$this->testStatus[$testName][$assertName]=array('pass','');
			}catch(Exception $e){
				$this->testStatus[$testName][$assertName]=array('fail',$e->getMessage());
			}
			// If we're negating, set the flag back over
			if($negate){
				$this->testStatus[$testName][$assertName][0]=($this->testStatus[$testName][$assertName][0]=='pass') ? 'fail' : 'pass';
			}
			return $this;
		}elseif(stristr($name,'marktest')){
			// catch our "marktestskipped" etc...
			preg_match('/marktest(.*)?/i',$name,$match);

			if(isset($match[1])){
				$findAttribute = 'stop-on-'.strtolower($match[1]);
				if(HelperArguments::getArgument($findAttribute)){
					die("\n!! Stop on ".strtolower($match[1])." : ".$testName." \nMessage: ".$arg[0]."\n\n");
				}
			}

			$this->testStatus[$testName][$name]=array($match[1],ucwords($arg[0]));
		}else{
			// assume it's an action
			$actionName='Action'.ucwords(strtolower($name));
			try {
				$actionObj = new $actionName(null,$arg);
				$actionObj::setCurrentMessage($currentMessage);
				$returnObj = $actionObj->execute();

				$this->testStatus[$testName][$actionName]=array('pass','');

				if(HelperArguments::getArgument('verbose')){
					array_push($this->testStatus[$testName][$actionName],
						$currentMessage::getData('__lastRequest'),
						$currentMessage::getData('__lastResponse')
					);
				};
			}catch(Exception $e){
				$this->testStatus[$testName][$actionName]=array('fail',$e->getMessage());
			}
			// return thyself!
			return $this;
		}
	}
}

?>
