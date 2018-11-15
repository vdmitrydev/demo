<?php

require __DIR__.'/../vendor/autoload.php';

$loggerFactory = new \App\Loggers\LoggerFactory();
$logger = $loggerFactory->build();

try {
    $app = new \App\Application(getopt('t:e::', ['type:', 'extra::']));
    $app
        ->addSubscriber(new \App\Subscribers\Clerk($logger))
        ->run();
} catch (Exception $e) {
    $logger->error($e->getMessage());
} finally {
    $logger->info('Exit');
}
