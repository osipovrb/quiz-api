<?

namespace App\Websocket;

use Ratchet\ConnectionInterface;
use Ratchet\RFC6455\Messaging\MessageInterface;
use Ratchet\WebSocket\MessageComponentInterface;
use App\Websocket\Chat\Commands;
use App\Websocket\Chat\Frame;

class Chat implements MessageComponentInterface
{
    public array $connections = [];

    public function onOpen(ConnectionInterface $connection)
    {
        // https://github.com/beyondcode/laravel-websockets/issues/342
        $socketId = sprintf('%d.%d', random_int(1, 1000000000), random_int(1, 1000000000));
        $connection->socketId = $socketId;
        $connection->app =  new \stdClass(); 
        $connection->app->id = 'chat';

        $this->attachConnection($connection);

        $frame = Frame::fromPayload('message', 'Connected! ID: '.$connection->socketId);
        $connection->send($frame->json_string);
    }
    
    public function onClose(ConnectionInterface $connection)
    {
        $this->detachConnection($connection);
    }

    public function onError(ConnectionInterface $connection, \Exception $e)
    {
        $frame = Frame::fromPayload('error', $e->getMessage());
        $connection->send($frame->json_string);
    }

    public function onMessage(ConnectionInterface $connection, MessageInterface $msg)
    {
        $frame = Frame::fromString($msg);
        if (!method_exists(Commands::class, $frame->command)) {
            throw new \Exception('Invalid command: '.$frame->command);
        }
        
        Commands::{ $frame->command }($this, $connection, $frame);
    }

    public function broadcast(Frame $frame) 
    {
        foreach ($this->connections as $connection) {
            $connection->send($frame->json_string);
        }
    }

    protected function attachConnection($connection) 
    {
        $this->connections[] = $connection;
    }

    protected function detachConnection($connection) 
    {
        $this->connections = array_filter($this->connections, function($c) use ($connection) {
            $c->socketId != $connection->socketId;
        });
    }
}
