<?php
/**
 * Helper for controlling output
 *
 * @author Chris Cornutt <ccornutt@phpdeveloper.org>
 * @package Frisk
 */
class HelperOutput extends Helper 
{
	/**
	 * Main execution method
	 *
	 * @param array $arguments Incoming arguments
	 * @return void
	 */
	public function execute($arguments)
	{
		
	}

	/**
	 * Check to see if the output format has a method to handle it
	 * Reflect on this class to get the methods...
	 *
	 * @param string $outputFormat Output format type (xml, csv, etc)
	 */
	public function allowedFormat($outputFormat)
	{
		$allowedFormat 	 = false;
		$reflectionClass = new ReflectionClass('HelperOutput');
		$classMethods 	 = $reflectionClass->getMethods();

		$methodMatch = 'as'.ucwords(strtolower($outputFormat));
		foreach($classMethods as $method){
			if($method->name==$methodMatch){ $allowedFormat=true; }
		}
		return $allowedFormat;
	}

	/**
	 * Abstraction method to get things to the right place
	 *
	 * @param array $executionData Results from test run
	 * @param string $outputFormat Requested output format
	 */
	public function output($executionData,$outputFormat)
	{
		$methodName = 'as'.strtolower(ucwords($outputFormat));	
		return self::$methodName($executionData);
	}
	
	/**
	 * Output the data array natively (default)
	 *
	 * @param array $outputData Output data
	 * @return array $outputData
	 */
	public static function asArray($outputData)
	{
		return $outputData;
	}
	
	/**
	 * Parse output and return as a CSV file (comma-delimited)
	 *
	 * @param array $outputData Output data array
	 * @return string $csvLines String containing newline-delimited, CSV formatted-lines
	 */
	public static function asCsv($outputData)
	{

		// loop through the array levels and built strings
		$allLines=array();
		foreach($outputData as $testKey => $test){
			$testClass = $testKey;
			foreach($test as $methodKey => $method){
				$testLines=array();
				$testMethod = $methodKey;
				foreach($method as $actionKey => $action){
					$testLines['class'] = $testClass;
					$testLines['method'] =$testMethod;
					$testLines['actionType'] = $actionKey;
					$testLines['status'] = $action[0];
					$testLines['message']= $action[1];
					
					$allLines[]='"'.implode('","',$testLines).'"';
				}
			}
		}
		return implode("\n",$allLines);
	}
	
	/**
	 * Parse output data and return as XML string
	 * 
	 * @param array $outputData Output data array
	 * @return void
	 */
	public static function asXml($outputData)
	{
		return 'XML output formatting not yet implemented';
	}
	
	/**
	 * Parse output and return as a JSON string
	 *
	 * @param array $outputData Output data array
	 * @return void
	 */
	public static function asJson($outputData)
	{
		return json_encode($outputData);	
	}

	/**
	 * Write the output data into a file - checks for permissions too
	 *
	 * @param string $fullPath Full path, including filename, to write to
	 * @param string $outputData Text data to write to the file
	 * @return void
	 * @throws Exception
	 */
	public static function writeToFile($fullPath,$outputData)
	{
		if(is_file($fullPath)){
			throw new Exception('File "'.$fullPath.'" exists! Please choose another filename.');
		}
		$directoryPath = substr($fullPath,0,strrpos($fullPath,'/'));

		if($directoryResource = opendir($directoryPath)){
			file_put_contents($fullPath,$outputData);
		}else{
			throw new Exception('Cannot write to location: '.$directoryPath);
		}
	}
}

?>
