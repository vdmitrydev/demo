<?php

namespace App\Loggers;

interface LoggerInterface
{
    /**
     * Log error
     *
     * @param string $message
     * @return mixed
     */
    public function error(string $message);

    /**
     * Log info
     *
     * @param string $message
     * @return mixed
     */
    public function info(string $message);

    /**
     * Log success
     *
     * @param string $message
     * @return mixed
     */
    public function success(string $message);
}
