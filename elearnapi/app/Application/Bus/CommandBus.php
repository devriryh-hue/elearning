<?php
// app/Application/Bus/CommandBus.php
namespace App\Application\Bus;

interface CommandBus
{
    public function dispatch(object $command): mixed;
}