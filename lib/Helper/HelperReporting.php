<?php

/**
 * Handle the requests for reporting of the results
 *
 * @author Chris Cornutt <ccornutt@phpdeveloper.org>
 * @package Frisk
 */
class HelperReporting extends Helper
{

	/**
	 * Local container for testing results
	 */
	private static $testingResults = array();

	/**
	 * Load testing results and call reporting method type
	 *
	 * @param array $testingResults Results from HTTP testing
	 * @param string $outputType[optional] Output format, defaults to HTML
	 * @throws Exception
	 */
	public function execute($testingResults,$outputType = 'html')
	{
		self::$testingResults=$testingResults;

		$methodName = 'report'.ucwords(strtolower($outputType));
		if(method_exists(__CLASS__,$methodName)){
			self::$methodName($testingResults);
		}else{
			throw new Exception(ucwords(strtolower($outputType)).' reporting not allowed.');
		}
	}

	/**
	 * Pull in the contents of the external template for the type
	 *
	 * @param string $reportType Type of report to generate
	 * @return string $reportData report template data
	 */
	public function loadReportTemplate($reportType = 'html')
	{
		$reportData = '';
		return $reportData;	
	}
	
	/**
	 * Combine the report template and the test results data
	 *
	 * @param string $reportType Report type to generate
	 * @param array $reportData Test run results
	 * @return templated results
	 */
	public function applyReportTemplate($reportType = 'html')
	{
		$template = self::loadReportTemplate($reportType);
		return null;
	}

	/**
	 * Reporting Output - HTML
	 *
	 * Used to generate HTML-based reports
	 * 
	 * @param array $results Testing results
	 * @return string HTML output
	 */
	public function reportHtml($results)
	{
		// conver this over with the two methods above
		$html=sprintf('
			<html>
			<head>
				<style>
				span.pass { background-color: #08DD08; }
				span.fail { background-color: #BB1111; }
				</style>
			</head>
			<body>
			<table cellpadding="3" cellspacing="0" border="1">
		');
		// output the results in a single HTML file
		foreach($results as $testName => $tests){
			$html.='<tr><td colspan="">'.$testName.'</tr>'."\n";
			foreach($tests as $methodName => $methods){
				foreach($methods as $resultName => $results){
					$html.='<tr>';
					$html.='<td>'.$methodName.'</td>'."\n";
					$html.='<td>'.$resultName.'</td>'."\n";
					$html.='<td><span class="'.strtolower($results[0]).'">'.$results[0].'</span></td>';
					$html.='<td>'.$results[1].'</td>'."\n";
					$html.='</tr>';
				}
			}
		}

		$html.=sprintf('
			</table>
			</body>
			</html>
		');

		echo $html;

		return $html;
	}
}

?>
