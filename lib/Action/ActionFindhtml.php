<?php

class ActionFindHtml extends Action
{

	public function execute()
	{
		$msgObj         = &parent::getCurrentMessage();
                $http           = $msgObj::getData('currentHttp');
                $arguments      = $msgObj::getData('currentArguments');	

		try {
			HelperFindHtml::execute($http->getBody());
			$found=HelperFindHtml::findByTag($arguments[0],$arguments[1]);
			if($found){
				$msgObj::setData('matchAgainst',$found);
			}
		}catch(Exception $e){
			throw new Exception($e->getMessage());
		}
	}

}

?>
