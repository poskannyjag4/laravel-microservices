<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

class ListenUserCreated extends Command
{
    const HOST = 'rabbitmq';

    const PORT = 5672;

    const USER = 'user';

    const PASS = 'root';

    const QUEUE = 'UserNotification';

    const DURATION = 3600;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'rabbitmq:listen';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Слушает события в rabbitmq';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $connection = new AMQPStreamConnection(self::HOST, self::PORT, self::USER, self::PASS);
        $channel = $connection->channel();

        $channel->queue_declare(self::QUEUE, false, false, false, false);

        echo " [*] Waiting for messages. To exit press CTRL+C\n";

        $callback = function (AMQPMessage $msg) {
            $userId = json_decode($msg->getBody())->id;

            Cache::put("users-{$userId}", $msg->getBody(), self::DURATION);
            echo "Пользователь с id {$userId} сохранен\n";

        };

        $channel->basic_consume(self::QUEUE, '', false, true, false, false, $callback);

        while ($channel->is_open()) {
            $channel->wait();
        }
    }
}
