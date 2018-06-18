<?php

namespace App\Services;

class KeyTokenizer
{

	protected $prefix = "ak";
	protected $length;
	
	public function __construct( int $length)
	{
		$this->length = $length;
	}

	public function generate()
	{
		$key = $this->prefix . '_' . bin2hex(random_bytes($this->length));

		return $key;
	}
}