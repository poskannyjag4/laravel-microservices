<?php

namespace App\Listeners;

use App\Events\UserCreated;
use App\Models\User;
use Exception;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
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

    /**
     * Handle the event.
     * @throws Exception
     */
    public function handle(UserCreated $event): void
    {
        $connection = new AMQPStreamConnection(self::HOST, self::PORT, self::USER, self::PASS);
        $channel = $connection->channel();

        $channel->queue_declare(self::QUEUE, false, false, false, false);

        $msg = new AMQPMessage($event->user);
        $channel->basic_publish($msg, '', self::QUEUE);
    }
}
