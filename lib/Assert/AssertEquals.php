<?php
/* Assert that value is equal to another given */

class AssertEquals extends Assert 
{
	public function assertSetup(){}
	public function assertTeardown(){}
	/**
	 * Evaluate that the two values given are equal
	 */
	public function assertExecute()
	{	
		if(count($this->assertArguments)==2){
			$compare1 = $this->assertArguments[0];
			$compare2 = $this->assertArguments[1];
		}elseif(count($this->assertArguments)==1 && isset($this->input)){
			$compare1 = $this->assertArguments[0];
			$compare2 = $this->input['httpOutput'];
		}else{
			throw new Exception(get_class().': Not enough parameters!');
		}
		
		if($compare1!=$compare2){
			throw new Exception(get_class().': Values not equal!');
		}
	}

}

?>
