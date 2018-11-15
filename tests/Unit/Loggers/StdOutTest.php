<?php

use App\Loggers\StdOut;
use PHPUnit\Framework\TestCase;

class StdOutTest extends TestCase
{
    public function testError()
    {
        $this->expectOutputString("\033[0;31m[ERROR]: some error\033[0m\n");
        $logger = new StdOut();
        $logger->error('some error');
    }

    public function testSuccess()
    {
        $this->expectOutputString("\033[0;32msome success\033[0m\n");
        $logger = new StdOut();
        $logger->success('some success');
    }

    public function testInfo()
    {
        $this->expectOutputString("some info\n");
        $logger = new StdOut();
        $logger->info('some info');
    }
}
