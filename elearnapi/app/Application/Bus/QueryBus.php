<?php
// app/Application/Bus/QueryBus.php
namespace App\Application\Bus;

interface QueryBus
{
    public function ask(object $query): mixed;
}