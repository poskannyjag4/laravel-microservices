<?php

namespace App\Listeners;

use App\Events\UserCreated;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

class NotifyServices
{
    const HOST = 'rabbitmq';

    const PORT = 5672;

    const USER = 'user';

    const PASS = 'root';

    const QUEUE = 'UserNotification';

    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    public function handle(UserCreated $event): void
    {
        $connection = new AMQPStreamConnection(self::HOST, self::PORT, self::USER, self::PASS);
        $channel = $connection->channel();

        $channel->queue_declare(self::QUEUE, false, false, false, false);

        $msg = new AMQPMessage($event->user);
        $channel->basic_publish($msg, '', self::QUEUE);
    }
}
