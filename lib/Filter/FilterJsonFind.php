<?php

class FilterJsonFind extends FilterIterator {

	public function __construct($iterator)
	{
		parent::__construct($iterator);
	}

	public function accept()
	{
		// TODO: make it actually take in data heh
		return ($this->current()=='data here');
	}

}

?>
