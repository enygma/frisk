<?php

/**
 * Handle the requests for reporting of the results
 *
 * @author Chris Cornutt <ccornutt@phpdeveloper.org>
 * @package Frisk
 */
class HelperReporting extends Helper
{

	private static $reportTemplateDir 	= '';
	private static $reportExportDir		= '';

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
		self::$reportTemplateDir 	= HelperConfig::getConfigValue('basedir').'/inc/report_templates';
		self::$reportExportDir 		= Helperconfig::getConfigValue('basedir').'/inc/report_export';
		self::$testingResults		= $testingResults;

		// Be sure the directories exist!
		if(!is_dir(self::$reportTemplateDir)){
			throw new Exception('Report template directory does not exist! ('.self::$reportTemplateDir.')');
		}
		if(!is_dir(self::$reportExportDir)){
			throw new Exception('Report export directory does not exist! ('.self::$reportExportDir.')');
		}

		$methodName = 'report'.ucwords(strtolower($outputType));
		if(method_exists(__CLASS__,$methodName)){
			$reportData = self::$methodName($testingResults);
			self::exportReport($reportData,$outputType);
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
		$filePath = self::$reportTemplateDir.'/'.ucwords(strtolower($reportType)).'Template.txt';
		return (is_file($filePath)) ? file_get_contents($filePath) : null;
	}
	
	/**
	 * Combine the report template and the test results data
	 *
	 * @param string $reportType Report type to generate
	 * @param array $reportData Test run results
	 * @return templated results
	 */
	public function applyReportTemplate($reportType = 'html',$reportData)
	{
		$template = self::loadReportTemplate($reportType);
		$replaceKeys = array_keys($reportData);
		foreach($replaceKeys as $index => $key){
			$replaceKeys[$index]='['.$key.']';	
		}
		$template = str_replace($replaceKeys,array_values($reportData),$template);

		return $template;
	}

	/**
	 * Export report data to the export directory by: date.type_ext
	 *
	 * @param string $reportData string of data resulting from applyTemplate
	 * @return void
	 */
	public function exportReport($reportData,$outputType)
	{
		$filePath = self::$reportExportDir.'/'.date('YmdHis').'.'.strtolower($outputType);
		file_put_contents($filePath,$reportData);
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
		$html='';
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
		$templateData = array('report_table_body'=>$html);
		return self::applyReportTemplate('html',$templateData);
	}
}

?>
