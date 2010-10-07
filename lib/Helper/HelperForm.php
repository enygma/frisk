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
	private $parsedHTML = null;

	/**
	* Load in the HTML and get it ready for the following calls
	*/
	public function execute($arguments){

		$htmlToParse 	= $arguments['httpBody'];
		$fieldName	= $arguments['fieldName'];

		$parsedToDom = new DOMDocument();
		$parsedToDom->loadHTML($htmlToParse);

		$xml=simplexml_import_dom($parsedToDom);
		$this->parsedHTML=$xml;

		$ret=$xml->xpath("//*[@id='".$fieldName."']");
		var_dump($ret);

	}

	/**
	* Check to see if a form field exists by that name
	*/
	public function isFormFieldByName($fieldName){
	}

	/**
	* Check to see if a form field wth that ID exists
	*/
	public function isFormFieldById($fieldId){
	}

}

?>
