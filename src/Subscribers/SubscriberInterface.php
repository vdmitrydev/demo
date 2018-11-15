<?php

namespace App\Subscribers;

interface SubscriberInterface
{
    /**
     * Notify subscriber about event
     *
     * @param string $event
     * @param array $data
     * @return mixed
     */
    public function notify(string $event, array $data = []);
}
