<?php

class MyTest extends Test
{	
	/*public function test1()
	{
		$this->assertEquals('test1','test2');
	}
	public function test2()
	{
		$this->post('/mypost.php','test.localhost',array('test'=>'foo this'))
			->assertContains('oo th');
	}
	public function test3()
	{
		$this->post('/exact.php','test.localhost',array('test'=>'foo this'))
			->assertEquals('foo this');
	}
	public function test4()
	{
		$this->get('/exact.php','test.localhost')
			->assertContains('just a get')
			->assertEquals('i think this is just a get');
	}
	public function test5()
	{
		$this->get('/','www.google.com')
			->assertResponseCode('300');
	}
	public function test6()
	{
		$this->get('/','www.google.com')
			->assertCookieIsSet('PREF_ID');
	}
	*/
	// this is going to be the tough one...
	public function test7()
	{
		$formData=array('mytest'=>'testing this');
		$this->get('/form_test.php','www.talkingpixels.org')
			->submitForm($formData)
			->assertContains("//*[@name='mytest']",TEST::TYPE_XPATH);
	}
	/*public function test8()
	{
		$this->get('/form_test.php?bar=1','www.talkingpixels.org')
			->assertContains('foo');
	}*/
	/*public function test9()
	{
		$this->get('/exact.php','test.localhost')
			->assertContains('just a noget')
			->assertEquals('i think this is just a get');
	}*/
	public function test10()
	{
		$this->markTestSkipped("I just can't finish it!");
	}
}

?>
