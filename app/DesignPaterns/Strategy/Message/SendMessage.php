<?php

namespace App\DesignPaterns\Strategy\Message;

use App\DesignPaterns\Strategy\Message\MessageInterface;

class SendMessage
{
    public $message;
    public $receiver;
    public $service;

    public function setReceiver($receiver)
    {
        $this->receiver = $receiver;
    }

    public function setMessage($message)
    {
        $this->message = $message;
    }

    public function setService(MessageInterface $service)
    {
        $this->service = $service;
    }

    public function sendMessage()
    {
        return $this->service->sendMessage($this->receiver, $this->message);
    }

    public function sendOtp()
    {
        return $this->service->sendOtp($this->receiver, $this->message);
    }

    public function sendMessageArray()
    {
        return $this->service->sendMessageArray($this->receiver, $this->message);
    }
}
