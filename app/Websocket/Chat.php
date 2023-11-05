<?

namespace App\Websocket;

use Ratchet\ConnectionInterface;
use Ratchet\RFC6455\Messaging\MessageInterface;
use Ratchet\WebSocket\MessageComponentInterface;

class Chat implements MessageComponentInterface
{

    public function onOpen(ConnectionInterface $connection)
    {
        // https://github.com/beyondcode/laravel-websockets/issues/342
        $socketId = sprintf('%d.%d', random_int(1, 1000000000), random_int(1, 1000000000));
        $connection->socketId = $socketId;
        $connection->app =  new \stdClass(); 
        $connection->app->id = 'chat';

        $connection->send('Connected');
    }
    
    public function onClose(ConnectionInterface $connection)
    {
        $connection->send('Disconnected');
    }

    public function onError(ConnectionInterface $connection, \Exception $e)
    {
        $connection->send($e->getMessage());
    }

    public function onMessage(ConnectionInterface $connection, MessageInterface $msg)
    {
        $connection->send('Echoing "'.$msg.'"');
    }
}
