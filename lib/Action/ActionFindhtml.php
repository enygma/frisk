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
			$found=HelperFindHtml::find($arguments[0],$arguments[1]);
		}catch(Exception $e){
			throw new Exception($e->getMessage());
		}
	}

}

?>