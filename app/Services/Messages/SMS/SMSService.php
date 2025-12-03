<?php

namespace App\Services\Messages\SMS;

use App\Services\Messages\MessageInterface;

class SMSService implements MessageInterface
{
    private string $receiver;
    private string $content;

    public function setReceiver($receiver)
    {
        $this->receiver = $receiver;
    }

    public function getReceiver()
    {
        return $this->receiver;
    }

    public function setContent($content)
    {
        $this->content = $content;
    }

    public function getContent()
    {
        return $this->content;
    }

    public function sendMessage()
    {
        $kaveNegarService = new KavehNegarService();
        $kaveNegarService->sendVerifySms($this->receiver, $this->content);
    }
}
