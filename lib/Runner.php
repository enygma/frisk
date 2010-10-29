<?php

/* Set up and run the defined tests */

class Runner
{	
	public $testsDir	= '';
	public $testResults = array();
	
	public function __construct(){

		if($testsDir = HelperArguments::getArgument('tests-dir')){
			$this->testsDir = $testsDir;
		}elseif($testsDir = HelperConfig::getConfigValue('tests_directory')){
			$this->testsDir = $testsDir;
		}else{
			$this->testsDir = __DIR__.'/../tests';
		}
	}
	
	public function execute($tests=null)
	{	
		// check to see if they spcified one specific test
		$singleTestName=HelperArguments::getArgument('test');
		
		$tests=$this->loadTests($singleTestName);
		
		if(empty($tests)){
			throw new Exception('Invalid or no tests found!');
		}
		
		foreach($tests as $test){
			$testObj = new $test();
			
			// We have our class of tests, reflect the methods and execute
			$ref = new ReflectionClass($testObj);
			$methods = $ref->getMethods();
		
			foreach($methods as $method){
				if($method->class==get_class($testObj)){
					// set up our message object
					$msgObj  = new Message();
					$msgObj::setCurrentTest($method->name);
					$testObj::setCurrentMessage($msgObj);
					
					$testObj->{$method->name}($msgObj);
					if(!empty($testObj->testStatus)){
						$this->testResults[$test]=$testObj->testStatus;
					}
				}
			}
		}
	}
	public function loadTests($singleTestName=null){
		$testFiles=array();
		$dir = new DirectoryIterator($this->testsDir);
		foreach($dir as $file){
			if(!$file->isDot() && strstr($file->getFilename(),'Test') && substr($file->getFilename(),-3)=='php'){
				$testName=str_replace('.php','',$file->getFilename());
				
				if($singleTestName && $testName==$singleTestName){
					$testFiles[]=$testName;
				}elseif(!$singleTestName){
					$testFiles[]=$testName;
				}
			}
		}
		return $testFiles;
	}
	
}

?>
