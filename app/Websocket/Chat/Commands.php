<?php

namespace App\Websocket\Chat;

use Ratchet\ConnectionInterface;
use App\Websocket\Chat;
use App\Websocket\Chat\Frame;


class Commands 
{
    public static function ping(Chat $chat, ConnectionInterface $connection, Frame $frame): void
    {
        $response = Frame::fromPayload('pong');
        $connection->send($response->json_string);
    }

    public static function message(Chat $chat, ConnectionInterface $connection, Frame $frame): void
    {
        $response = Frame::fromPayload('message', $frame->payload);
        $chat->broadcast($response);
    }
    
}
