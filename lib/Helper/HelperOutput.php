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
		$csvLines = array();
		
		return implode("\n",$csvLines);
	}
	
	/**
	 * Parse output data and return as XML string
	 * 
	 * @param array $outputData Output data array
	 * @return void
	 */
	public static function asXml($outputData)
	{
		
	}
	
	/**
	 * Parse output and return as a JSON string
	 *
	 * @param array $outputData Output data array
	 * @return void
	 */
	public static function asJson($outputData)
	{
		
	}
}

?>