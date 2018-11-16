<?php

namespace App\Loggers;

class StdOut implements LoggerInterface
{
    const RED_COLOR = '0;31';
    const GREEN_COLOR = '0;32';

    /**
     * Output error
     *
     * @param string $message
     */
    public function error(string $message)
    {
        $this->log("[ERROR]: {$message}", self::RED_COLOR);
    }

    /**
     * Output info
     *
     * @param string $message
     */
    public function info(string $message)
    {
        $this->log($message);
    }

    /**
     * Output success
     *
     * @param string $message
     */
    public function success(string $message)
    {
        $this->log($message, self::GREEN_COLOR);
    }

    /**
     * Output message
     *
     * @param string $message
     * @param string $color
     */
    private function log(string $message, string $color = '')
    {
        $string = '' === $color ? $message : "\033[" . $color . 'm' . $message . "\033[0m";

        echo $string . "\n";
    }
}
