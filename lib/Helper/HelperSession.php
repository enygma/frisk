<?php
/**
 * Handles session information for the current HTTP connection
 *
 * @author Chris Cornutt <ccornutt@phpdeveloper.org>
 * @package Frisk
 */
class HelperSession extends Helper
{

	static $currentSessionId = null;

	/**
	 * Loops through headers to see of there's a session ID
	 * If no session information is provided, currentSessionId will remain null
	 *
	 * @param array $httpHeaders HTTP headers from a HttpResponse
	 */
	public function execute($httpHeaders)
	{
		foreach($httpHeaders as $headerKey => $headerValue){
			if($headerKey=='Set-Cookie' && strpos($headerValue,'PHPSESSID')!==false){
				$cookieParts=explode(';',str_replace('PHPSESSID=','',$headerValue));
				self::startSession(trim($cookieParts[0]));
			}
		}
	}

	/**
	 * Set the current session cookie value
	 *
	 * @param string $sessionId PHP Session ID
	 * @return null
	 */
	public static function startSession($sessionId)
	{
		self::$currentSessionId = $sessionId;
	}

	/**
	 * Grab the current session ID and return it
	 *
	 * @return string/null
	 */
	public static function getCurrentSession()
	{
		return self::$currentSessionId;	
	
	}

	/**
	 * Ends the session (unsets the variable)
	 *
	 * @return void
	 */
	public static function endSession()
	{
		self::$currentSessionId - null;
	}

}

?>
