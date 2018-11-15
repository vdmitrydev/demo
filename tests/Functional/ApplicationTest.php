<?php

use App\Application;
use App\Exceptions\DataSourceTypeNotProvidedException;
use App\Exceptions\InvalidDataSourceTypeException;
use App\Subscribers\SubscriberInterface;
use PHPUnit\Framework\TestCase;
use org\bovigo\vfs\vfsStream;

class ApplicationTest extends TestCase
{
    public function testConstructorFailIfDataSourceTypeIsNotProvided()
    {
        $this->expectException(DataSourceTypeNotProvidedException::class);
        new Application([]);
    }

    public function testRunFailWithUnknownDataSourceType()
    {
        $this->expectException(InvalidDataSourceTypeException::class);
        $app = new Application(['t' => 'unknown_data_source_type']);
        $app->run();
    }

    public function testAddSubscriber()
    {
        $app = new Application(['t' => 'file']);

        $subscriberMock = $this->getMockBuilder(SubscriberInterface::class)
            ->setMethods(['notify'])
            ->getMock();

        $prevSubscribersCount = count($app->getSubscribers());
        $app->addSubscriber($subscriberMock);

        $subscribers = $app->getSubscribers();
        $curSubscribersCount = $this->count($subscribers);

        $this->assertSame($subscriberMock, $subscribers[count($subscribers) - 1]);
        $this->assertSame($prevSubscribersCount + 1, $curSubscribersCount);
    }

    public function testRunWithFile()
    {
        $directory = [
            'file.txt' => "1\n2\n3"
        ];

        $fileSystem = vfsStream::setup('test', 444, $directory);

        $subscriberMock = $this->getMockBuilder(SubscriberInterface::class)
            ->setMethods(['notify'])
            ->getMock();

        $subscriberMock->expects($this->exactly(3))
            ->method('notify')
            ->withConsecutive(
                [$this->equalTo('start'), $this->equalTo([])],
                [$this->equalTo('before_count'), $this->equalTo([])],
                [$this->equalTo('finish'), $this->equalTo(['result' => 3])]
            );

        $app = new Application(['t' => 'file', 'e' => $fileSystem->url() . '/file.txt']);
        $app->addSubscriber($subscriberMock);
        $this->assertSame(3, $app->run());
    }
}
