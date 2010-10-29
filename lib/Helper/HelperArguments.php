<?php
/**
 * Helper to work wth arguments called on execution
 *
 * @author Chris Cornutt <ccornutt@phpdeveloper.org>
 * @pacakge Frisk
 */
class HelperArguments extends Helper
{

	/**
	 * Details for current arguments (spit out on call to --help)
	 * @var array
	 */
	static $argumentDetails = array(
		'quiet'	  	=> array('','Suppresses all output, nothing returned'),
		'output'  	=> array('[type]','Controls output format (ex. XML, JSON, CSV, etc)'),
		'config'  	=> array('[file path]','Path to the configuration file to use'),
		'test'	  	=> array('[test name]','Run a single test with this name'),
		'verbose' 	=> array('','Add more verbose reporting to testing output'),
		'tests-dir' 	=> array('[path]','Definite the directory to pull tests from'),
		'help'	  	=> array('','Display this message')
	);

	/**
	 * Arguments currently defined on execution
	 * @var array
	 */
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
				$argumentKey    = str_replace('--','',$argumentParts[0]);

				self::$currentArguments[$argumentKey] = (count($argumentParts)>1) ? $argumentParts[1] : true;
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
		return (isset(self::$currentArguments[$name])) ? self::$currentArguments[$name] : null;
	}

	/**
	 * Display the contents of the argument detail from above
	 *
	 * @return void
	 */
	public static function displayArgumentHelp()
	{
		echo "Frisk command-line parameters:\n";
		echo "=======================================\n";

		// find the longest argument info
		$longestParameter = 0;
		foreach(self::$argumentDetails as $argumentName => $argumentDetail){
			$argumentString='--'.$argumentName.((!empty($argumentDetail[0])) ? ' = '.$argumentDetail[0] :  '');
			if(strlen($argumentString)>$longestParameter){ $longestParameter=strlen($argumentString); }
		}

		foreach(self::$argumentDetails as $argumentName => $argumentDetail){
			$argumentString="--".$argumentName.' ';
			if(!empty($argumentDetail[0])){ $argumentString.='= '.$argumentDetail[0]; }

			echo str_pad($argumentString,$longestParameter+3,' ').$argumentDetail[1]."\n";
		}
		echo "\n\n";
	}
}

?>
