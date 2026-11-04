<?php
// app/Application/Bus/LaravelBus.php
namespace App\Application\Bus;

use Illuminate\Contracts\Container\Container;

final class LaravelBus implements CommandBus, QueryBus
{
    public function __construct(private Container $container) {}

    public function dispatch(object $command): mixed
    {
        return $this->handle($command, 'Command');
    }

    public function ask(object $query): mixed
    {
        return $this->handle($query, 'Query');
    }

    private function handle(object $message, string $suffix): mixed
    {
        // CreateCourseCommand -> CreateCourseHandler
        $handlerClass = preg_replace("/{$suffix}$/", 'Handler', $message::class);

        return $this->container->make($handlerClass)($message);
    }
}