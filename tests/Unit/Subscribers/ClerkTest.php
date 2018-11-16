<?php

use App\Subscribers\Clerk;
use App\Loggers\LoggerInterface;
use PHPUnit\Framework\TestCase;

class ClerkTest extends TestCase
{
    /** @var \PHPUnit\Framework\MockObject\MockObject */
    private $loggerMock;

    public function setUp()
    {
        $this->loggerMock = $this->getMockBuilder(LoggerInterface::class)
            ->setMethods(['info', 'success', 'error'])
            ->getMock();
    }
    /**
     * @dataProvider notifyDataProvider
     */
    public function testNotify($event, $data, $message, $method)
    {
        $this->loggerMock->expects($this->once())
            ->method($method)
            ->with($this->equalTo($message));

        $clerk = new Clerk($this->loggerMock);
        $clerk->notify($event, $data);
    }

    public function testNotifyWithUnknownEvent()
    {
        $this->loggerMock->expects($this->never())
            ->method('info');

        $this->loggerMock->expects($this->never())
            ->method('success');

        $this->loggerMock->expects($this->never())
            ->method('error');

        $clerk = new Clerk($this->loggerMock);
        $clerk->notify('unknown_event');
    }

    public function notifyDataProvider() {
        return [
            ['start', [], 'Initializing data source...', 'info'],
            ['before_count', [], 'Data source is set, starting to count...', 'info'],
            ['finish', ['result' => 50], 'The number of objects is 50', 'success'],
        ];
    }
}
