<?php

class FilterJsonFindExact extends FilterIterator {

	private $searchTerm = null;
	private $searchKey  = null;

	public function __construct($iterator,$searchTerm,$searchKey)
	{
		parent::__construct($iterator);
		$this->searchTerm = $searchTerm;
		$this->searchKey  = $searchKey;
	}

	public function accept()
	{
		return (preg_match('/^'.$this->searchTerm.'/',$this->current()) && $this->key() == $this->searchKey) ? true : false;
	}

}

?>
