<?php

/* Set up and run the defined tests */

class Runner
{	
	public $testsDir	= '';
	public $testResults = array();
	
	public function __construct(){
 		$this->testsDir = HelperConfig::getConfigValue('tests_directory');
	}
	
	public function execute($tests=null)
	{	
		$tests=$this->loadTests();
		
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
	public function loadTests(){
		$testFiles=array();
		$dir = new DirectoryIterator($this->testsDir);
		foreach($dir as $file){
			if(!$file->isDot() && strstr($file->getFilename(),'Test') && substr($file->getFilename(),-3)=='php'){
				$testFiles[]=str_replace('.php','',$file->getFilename());
			}
		}
		return $testFiles;
	}
	
}

?>
