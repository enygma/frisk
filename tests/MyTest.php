<?php
/**
 * Here's a few sample tests to get you started.
 * Each has a comment describing what it does and shows
 *
 * @author Chris Cornutt <ccornutt@phpdeveloper.org>
 * @package Frisk
 */
class MyTest extends Test
{	
	/**
	 * Test checks two strings against each other
	 * Result: FAIL
	 */
	public function test1()
	{
		$this->assertEquals('test1','test2');
	}

	/**
	 * Test makes a POST request to the mypost.php page
	 * Result: (depends on script being in place)
	 */
	public function test2()
	{
		$this->post('/mypost.php','test.localhost',array('test'=>'foo this'))
			->assertContains('oo th');
	}

	/**
	 * Test POSTs to remote script to see if response matches exactly
	 * Result: PASS
	 */
	public function test3()
	{
		$this->post('/exact.php','test.localhost',array('test'=>'foo this'))
			->assertEquals('foo this');
	}

	/**
	 * Test makes a GET request for a page and checks two things, a contains 
	 * and a equals (exact match)
	 * Result: PASS
	 */
	public function test4()
	{
		$this->get('/exact.php','test.localhost')
			->assertContains('just a get')
			->assertEquals('i think this is just a get');
	}

	/**
	 * Test makes a GET request to Google and checks the response code
	 * Result: PASS
	 */
	public function test5()
	{
		$this->get('/','www.google.com')
			->assertResponseCode('300');
	}

	/**
	 * Test makes a GET request to Google and checks for a cookie to be set
	 * Result: PASS
	 */
	public function test6()
	{
		$this->get('/','www.google.com')
			->assertCookieIsSet('PREF_ID');
	}

	/**
	 * Test makes a GET request on a page with a form, then sends form data
	 * In the response, it checks (using an XPath expression) for a field named "mytest"
	 * Result: PASS
	 */
	public function test7()
	{
		$formData=array('mytest'=>'testing this');
		$this->get('/form_test.php','www.talkingpixels.org')
			->submitForm($formData)
			->assertContains("//*[@name='mytest']",TEST::TYPE_XPATH);
	}
	
	/**
	 * Test makes a request to a remote page with a GET variable and checks response
	 * Result: PASS
	 */
	public function test8()
	{
		$this->get('/form_test.php?bar=1','www.talkingpixels.org')
			->assertContains('foo');
	}
	
	/**
	 * Test makes a GET request to a page, checks if it contains a string
	 * then checks to see if the content is an exact match
	 *
	 */
	public function test9()
	{
		$this->get('/exact.php','test.localhost')
			->assertContains('just a noget')
			->assertEquals('i think this is just a get');
	}
	
	/**
	 * Test shows an example of marking a test as skipped
	 * Result: SKIPPED
	 */
	public function test10()
	{
		$this->markTestSkipped("I just can't finish it!");
	}

	/**
	 * Test makes GET request to remote page and searches for a DIV tag
	 * on the page with an id of "test". It evaluates the content of that DIV
	 * to see if it contains the string
	 * Result: PASS
	 */
	public function test11(){
		$ret=$this->get('/find_test.php','www.talkingpixels.org')
			->findHtml('div',array('id'=>'test'))
			->assertContains('find thinks');
	}

	/**
	 * Test makes GET request to remote page, looks for link with ID of "myLink"
	 * It then "clicks" on the link to go to the next page and looks for the term "blah"
	 * Result: PASS
	 */
	public function test12(){
		$this->get('/find_test.php','www.talkingpixels.org')
			->clickLink('mylink')
			->assertContains('blah');
	}
	
	/**
	 * Test maes a POST of raw data ($postData) to a remote server. The "json" parameter
	 * tells it how to interpret the response message. The assertion then follows the "path"
	 * its given to see if the object at that location contains the value.
	 *
	 * Result: PASS
	 */
	public function test13(){
		$postData='{"post":"data here"}';
		$this->post('/raw_post.php','www.talkingpixels.org',$postData,'json')
			->assertContains('valfoo','showme_header1/sub2');	
	}
}

?>
