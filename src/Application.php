<?php

namespace App;

use App\DataSource\DataSourceFactoryInterface;
use App\Exceptions\DataSourceTypeNotProvidedException;
use App\Exceptions\InvalidDataSourceTypeException;
use App\Subscribers\SubscriberInterface;

class Application
{
    /**
     * Application options obtained from command line
     *
     * @var array
     */
    private $options;

    /**
     * @var SubscriberInterface[]
     */
    private $subscribers = [];

    /**
     * Create application
     *
     * @throws DataSourceTypeNotProvidedException
     * @param array $options
     */
    public function __construct(array $options)
    {
        if (!isset($options['t']) && !isset($options['type'])) {
            throw new DataSourceTypeNotProvidedException('data source type is not provided');
        }

        $this->options = $options;
    }

    /**
     * Run the application, count the number of objects
     *
     * @return int
     */
    public function run()
    {
        $this->broadcast('start');
        $dataSource = $this->getDataSourceFactory()->build($this->getExtraData());
        $this->broadcast('before_count');
        $result = $dataSource->count();
        $this->broadcast('finish', ['result' => $result]);

        return $result;
    }

    /**
     * Add event subscriber
     *
     * @param SubscriberInterface $subscriber
     * @return $this
     */
    public function addSubscriber(SubscriberInterface $subscriber)
    {
        $this->subscribers[] = $subscriber;

        return $this;
    }

    /**
     * Get event subscribers
     *
     * @return SubscriberInterface[]
     */
    public function getSubscribers(): array
    {
        return $this->subscribers;
    }

    /**
     * Notify subscribers about event
     *
     * @param string $event
     * @param array $data
     */
    private function broadcast(string $event, array $data = [])
    {
        foreach ($this->subscribers as $observer) {
            $observer->notify($event, $data);
        }
    }

    /**
     * Get data source factory
     *
     * @throws InvalidDataSourceTypeException
     * @return DataSourceFactoryInterface
     */
    private function getDataSourceFactory(): DataSourceFactoryInterface
    {
        $factoryName = str_replace('_', '', ucwords($this->getDataSourceType(), '_')) . 'Factory';
        $factoryClass = '\\App\\DataSource\\' . $factoryName;

        if (!class_exists($factoryClass)) {
            throw new InvalidDataSourceTypeException('data source not found');
        }

        return new $factoryClass();
    }

    /**
     * Get data source type by -t|--type option
     *
     * @return string
     */
    private function getDataSourceType(): string
    {
        return $this->options['t'] ?? $this->options['type'];
    }

    /**
     * Get extra data by -e|--extra option
     *
     * @return string
     */
    private function getExtraData(): string
    {
        return $this->options['e'] ?? $this->options['extra'] ?? '';
    }
}
