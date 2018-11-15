<?php

namespace App\Subscribers;

use App\Loggers\LoggerInterface;

class Clerk implements SubscriberInterface
{
    const EVENT_MESSAGES = [
        'start' => 'Initializing data source...',
        'before_count' => 'Data source is set, starting to count...',
        'finish' => 'The number of objects is %result%'
    ];

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * Create clerk
     *
     * @param LoggerInterface $logger
     */
    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    /**
     * Log the event
     *
     * @param string $event
     * @param array $data
     */
    public function notify(string $event, array $data = [])
    {
        if ($this->mustRespondToEvent($event)) {
            $this->logger->{$this->getLoggerMethod($data)}($this->getMessageText($event, $data));
        }
    }

    /**
     * Determine if clerk must respond to event
     *
     * @param string $event
     * @return bool
     */
    private function mustRespondToEvent(string $event): bool
    {
        return isset(self::EVENT_MESSAGES[$event]);
    }

    /**
     * Get message text that will be logged
     *
     * @param string $event
     * @param array $data
     * @return string
     */
    private function getMessageText(string $event, array $data): string
    {
        $placeholders = $substitutions = [];

        foreach ($data as $key => $value) {
            $placeholders[] = '%' . $key . '%';
            $substitutions[] = $value;
        }

        return str_replace($placeholders, $substitutions, self::EVENT_MESSAGES[$event]);
    }

    /**
     * Get logger method
     *
     * @param array $data
     * @return string
     */
    private function getLoggerMethod(array $data): string
    {
        return isset($data['result']) ? 'success' : 'info';
    }
}
