<?php

namespace App\WebSocket\Chat;

use App\WebSocket\Chat\Commands;

class Frame
{
    public $json_string;
    public $command;
    public $payload;

    public static function fromString(string $json_string): self
    {
        $frame = new self();
        $frame->json_string = $json_string;
        
        $decoded = $frame->decode();

        return $frame;
    }

    public static function fromPayload(string $command, mixed $payload = ''): self
    {
        $frame = new self();
        $frame->command = $command;
        $frame->payload = $payload;

        $frame->encode();
        
        return $frame;
    }

    public function decode(): self
    {
        if (!$decoded_msg = json_decode($this->json_string, true)) {
            throw new \Exception('Could not decode message');
        }

        $this->command = $decoded_msg['command'];
        $this->payload = $decoded_msg['payload'];

        return $this;
    }

    public function encode(): self 
    {
        $command = $this->command;
        $payload = $this->payload;

        if (!$json_string = json_encode(compact('command', 'payload'))) {
            throw new \Exception('Could not encode message');
        }

        $this->json_string = $json_string;

        return $this;
    }

}
