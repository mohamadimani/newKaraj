<?php

namespace App\DesignPaterns\Strategy\Message;

interface MessageInterface
{
    public function sendMessage($to, $message);

    public function sendOtp($to, $message);

    public function sendMessageArray(array $to, array $message);
}
