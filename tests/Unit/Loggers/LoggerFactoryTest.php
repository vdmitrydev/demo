<?php

use App\Loggers\LoggerFactory;
use App\Loggers\LoggerInterface;
use PHPUnit\Framework\TestCase;

class LoggerFactoryTest extends TestCase
{
    public function testBuild()
    {
        $factory = new LoggerFactory();
        $logger = $factory->build();
        $this->assertInstanceOf(LoggerInterface::class, $logger);
    }
}
