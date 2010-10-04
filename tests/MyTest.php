<?php

class MyTest extends Test
{	
	public function test1()
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
			->assertContains('just a get');
	}
	
}

?>