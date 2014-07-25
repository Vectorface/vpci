<?php

namespace Vectorface\vpci\tests\concrete\helpers;

class DateTimeHelper extends \DateTime
{
    private $timestamp;

    public function __construct()
    {
        $date = new \DateTime();
        $this->timestamp = $date->getTimestamp();
    }

    public function changeTime($ts)
    {
        $this->timestamp = $ts;
    }

    public function getTimestamp()
    {
        return $this->timestamp;
    }
}
