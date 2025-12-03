<?php

namespace App\Services\Messages;

class MessageService
{

    public function __construct(private MessageInterface $message) {}

    public function send()
    {
        $this->message->sendMessage();
    }
}
