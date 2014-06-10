<?php

namespace VF\vpci\tests\concrete\helpers;

class DateTimeHelper extends \DateTime
{
	private $timestamp;

	public function __construct()
	{
		$date = new \DateTime();
		$this->timestamp = $date->getTimestamp();
	}

	public function change_time($ts)
	{
		$this->timestamp = $ts;
	}

	public function getTimestamp()
	{
		return $this->timestamp;
	}
}