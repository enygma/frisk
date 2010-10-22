<?php

class FilterJsonFind extends FilterIterator 
{

	private $searchTerm = null;

	public function __construct($iterator,$searchTerm)
	{
		parent::__construct($iterator);
		$this->searchTerm = $searchTerm;
	}

	public function accept()
	{
		return ($this->current()==$this->searchTerm);
	}

}

?>
