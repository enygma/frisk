<?php

class AssertContains extends Assert
{
	public function assertSetup(){}
	public function assertTeardown(){}
	/**
	 * Evaluate that the two values given are equal
	 */
	public function assertExecute()
	{	
		// If we have a direct arguments, give preference
		if(count($this->assertArguments)>2){
			$find		= $this->assertArguments[0];
			$findin		= $this->assertArguments[1];
		}elseif(count($this->assertArguments)==1 && isset($this->input)){
			$find		= $this->assertArguments[0];
			$findin		= $this->input['httpOutput'];
		}else{ throw new Exception(get_class().''); }
		
		// check to see if it's there!
		if(!stristr($findin,$find)){
			throw new Exception(get_class().': Term not found');
		}
	}
}

?>