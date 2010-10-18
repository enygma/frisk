<?php

class HelperInput extends Helper
{
	public function execute($arguments)
	{
		//nothing to see
	}

	/**
	 * Takes in a XML string, parses out to SimpleXML object
	 *
	 * @param string $inputString XML string
	 * @return object $xml SimpleXML object
	 */
	public static function asXml($inputString)
	{
		return simplexml_load_string($inputString);	
	}
	
	/**
	 * Takes in a JSON string, parses into object
	 *
	 * @param string $inputString JSON string
	 * @return object $json
	 */
	public static function asJson($inputString)
	{
		return json_decode($inputString);	
	}
}

?>
