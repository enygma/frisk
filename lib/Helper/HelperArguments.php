<?php
/**
 * Helper to work wth arguments called on execution
 *
 * @author Chris Cornutt <ccornutt@phpdeveloper.org>
 * @pacakge Frisk
 */
class HelperArguments extends Helper
{

	static $currentArguments = array();

	/**
	 * Parse the arguments out and put them into our local array
	 *
	 * Arguments will come in in the format: --(.*)?=(.*?)
	 */
	public function execute($arguments)
	{
		if(!empty($arguments)){
			foreach($arguments as $argument){
				$argumentParts	= explode('=',$argument);
				if(count($argumentParts)>1){
					$argumentKey	= str_replace('--','',$argumentParts[0]);
					self::$currentArguments[$argumentKey]=$argumentParts[1];
				}
			}
		}
	}

	/**
	 * Add a new argument to the stack
	 *
	 * @param string $name Name of argument
	 * @param mixed $value Value to place in argument (probably string)
	 */
	public static function setArgument($name,$value)
	{
		self::$currentArguments[$name]=$value;
	}

	/**
	 * Remove an argument's information from the current list
	 *
	 * @param string $name Name of argument
	 */
	public static function deleteArgument($name)
	{
		unset(self::$currentArguments);
	}

	/**
	 * Check to see if the argument exists in the list
	 *
	 * @param string $name Name of the argument
	 */
	public static function isArgument($name)
	{
		return (isset(self::$currentArguments)) ? true : false;
	}

	/**
	 * Return the value of the argument, if it exists. 
	 * Otherwise, return NULL
	 *
	 * @param string $name Argument name
	 */
	public static function getArgument($name)
	{
		return (isset(self::$currentArguments)) ? self::$currentArguments : null;
	}

}

?>
