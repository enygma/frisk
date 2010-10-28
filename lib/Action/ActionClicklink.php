<?php
/**
 * Action to simulate the click of a link. Makes a GET
 * request to the contents of the link's href
 *
 * @author Chris Cornutt <ccornutt@phpdeveloper.org>
 * @package Frisk
 */
class ActionClickLink extends Action 
{

	public function execute()
	{
		$msgObj         = &parent::getCurrentMessage();
                $http           = $msgObj::getData('currentHttp');
                $arguments      = $msgObj::getData('currentArguments');	

		// see if it was a GET or a POST
		if($hostname=$msgObj::getData('getHost')){
			$location = $msgObj::getData('getLocation');
		}else{
			$hostname = $msgObj::getData('postHost');
			$location = $msgObj::getData('postLocation');
		}

		// now, find our link!
		HelperFindHtml::execute($http->getBody());
		preg_match('/href="(.*?)"/',HelperFindHtml::findByTag('a',array('id'=>$arguments[0])),$match);

		if(!isset($match[1])){
			// a link with the ID wasn't found - let's see if we can find it by the link contents
			$xpathExpression = "//a[text()='".$arguments[0]."']/@href";
			preg_match('/href="(.*?)"/',HelperFindHtml::findByExpression($xpathExpression),$match);
			if(!isset($match[1])){
				throw new Exception(get_class().': Link data not found!');
			}else{
				$linkLocation = $match[1];
			}
		}else{ $linkLocation = $match[1]; }

		$url		= parse_url($linkLocation);
		$hostname 	= (isset($url['host'])) ? $url['host'] : $hostname;

		$getArguments 	= array($linkLocation,$hostname);
		$msgObj::setData('currentArguments',$getArguments);
		ActionGet::execute();
	}	

}

?>
