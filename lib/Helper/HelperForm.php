<?php

/**
* Form helper class for parsing HTML documents
*
* @author Chris Cornutt <ccornutt@phpdeveloper.org>
* @package Frisk
*
*/

class HelperForm extends Helper 
{
	static $parsedHTML 	= null;
	static $inputTypes	= array('input','textarea');
	static $postData	= array();

	/**
	* Load in the HTML and get it ready for the following calls
	*/
	public function execute($arguments){
		
		$htmlToParse 	= $arguments['httpBody'];
		
		//echo $htmlToParse;

		$parsedToDom = new DOMDocument();
		@$parsedToDom->loadHTML($htmlToParse);

		$xml=simplexml_import_dom($parsedToDom);
		self::$parsedHTML=$xml;
	}

	/**
	* Check to see if a form field exists by that name
	*/
	public static function isFormField($fieldName,$fieldType='name'){
		$matches=self::$parsedHTML->xpath("//*[@".$fieldType."='".$fieldName."']");
		// check to ensure that there's at least one matching
		if($matches && count($matches)>0){
			foreach($matches as $match){
				if(in_array(strtolower($match->getName()),self::$inputTypes)){
					return true;
				}
			}
		}else{ return false; }
	}

	/**
	* Check to see if a form field with that ID exists
	*/
	public static function isFormFieldById($fieldId){
		return self::isFormField($fieldId,'id');
	}
	
	public static function setFormData($formData){
		self::$postData = $formData;
	}

}

?>
