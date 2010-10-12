<?php

class HelperFindHtml extends Helper
{
	static $parsedHtml	= null;

	/**
	 * Takes in HTML markup and converts it into an object
	 * 
	 * @param string $httpBody HTTP markup (plain-text)
	 */
	public function execute($httpBody)
	{
		self::$parsedHtml=HelperForm::parseHTML($httpBody);	
	}

	/**
	 * Generic "find" function that takes in the tag name
	 * and an array of attributes to look for in the page
	 *
	 * We only want to find one unique one right now
	 *
	 */
	public function find($htmlTag,$htmlOptions)
	{
		$expression='//*[';
		$expressionList=array();
		foreach($htmlOptions as $attributeType => $attributeValue){
			$expressionList[]='@'.$attributeType."='".$attributeValue."'";
		}
		$expression.=implode(' and ',$expressionList);
		$expression.=']';

		$foundTag = self::$parsedHtml->xpath($expression);

		if(count($foundTag)>1){
			throw new Exception('More than one match! Not allowed!');
		}

		// we've found our single object - set the match HTML
		return (isset($foundTag[0])) ? $foundTag[0]->asXML() : null;
	}
}

?>
