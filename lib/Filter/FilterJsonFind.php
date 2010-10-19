<?php

class FilterJsonFind extends FilterIterator {

	public function __construct($iterator)
	{
		var_dump($iterator);
		parent::__construct($iterator);
	}

	public function accept()
	{
		echo 'accept';
		echo 'c:'.$this->getInnerIterator()->current();
		return ($this->current()=='data here');
	}

}

?>
