<?php

final class Logger
{
    public string $name;

    public array $log;

    public function __construct(string $name)
    {
        $this->name = $name;
        $this->log = array();
    }

    public function addMessage(string $message)
    {
        $this->log[] = $message;
    }
}
