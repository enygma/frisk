<?php

/* Set up and run the defined tests */

class Runner
{	
	/**
	 * Path to locate tests
	 * @var string
	 */
	public $testsDir	= '';

	/**
	 * Contains test results (pass/fail & mesasges) 
	 * @var array
	 */
	public $testResults 	= array();

	/**
	 * If defined, only tests with names in array should be run
	 * @var array
	 */
	private $runOnly	= array();
	
	/**
	 * @throws Exception
	 */
	public function __construct(){

		if($testsDir = HelperArguments::getArgument('tests-dir')){
			$this->testsDir = $testsDir;
		}elseif($testsDir = HelperConfig::getConfigValue('tests_directory')){
			$this->testsDir = $testsDir;
		}else{
			$this->testsDir = __DIR__.'/../tests';
		}
		// check for a suite
		if($suite=HelperArguments::getArgument('suite')){
			try {
				HelperSuite::execute();
			}catch(Exception $e){
				throw new Exception($e->getMessage());
			}
                        $suiteConfig = HelperSuite::findSuiteConfigByName($suite);
			$this->testsDir = $suiteConfig['directory'];
			if(isset($suiteConfig['tests'])){ $this->runOnly = explode(',',$suiteConfig['tests']); }
		}
		set_include_path(get_include_path().PATH_SEPARATOR.$this->testsDir);
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
				
				if(!$this->isTestRunOnly($testName)){ continue; }
				
				if($singleTestName && $testName==$singleTestName){
					$testFiles[]=$testName;
				}elseif(!$singleTestName){
					$testFiles[]=$testName;
				}
			}
		}
		return $testFiles;
	}
	
	public function isTestRunOnly($testName)
	{
		if(empty($this->runOnly)){ return true; }

		// check for exact match first
		if(in_array($testName,$this->runOnly)){
			return true;
		}
		// try it out as a regex
		foreach($this->runOnly as $testMatch){
			if(@preg_match($testMatch,$testName)){
				return true;
			}
		}
		return false;
	}
	
}

?>
