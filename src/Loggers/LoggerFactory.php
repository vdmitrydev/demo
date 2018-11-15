<?php

namespace App\Loggers;

class LoggerFactory
{
    /**
     * Create logger
     *
     * @return LoggerInterface
     */
    public function build(): LoggerInterface
    {
        return new StdOut();
    }
}
