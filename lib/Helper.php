<?php
/**
 * Abstract Helper class
 *
 * NOTE: The Helper classes DO NOT have access to the Message objects
 * Values must be returned to the Action/Assertion and assigned there
 *
 * @author Chris Cornutt <ccornutt@phpdeveloper.org>
 */
abstract class Helper {

	abstract protected function execute($arguments);

}

?>
