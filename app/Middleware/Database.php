<?php

namespace App\Middleware;

use Fermi\Framework;
use Illuminate\Database\Capsule\Manager;
use Interop\Http\ServerMiddleware\DelegateInterface;
use Interop\Http\ServerMiddleware\MiddlewareInterface;
use Psr\Http\Message\ServerRequestInterface;

class Database implements MiddlewareInterface
{
    /**
     * Invoke the router via middleware.
     *
     * @param  Psr\Http\Message\RequestInterface $request PSR-7 compliant request.
     * @param  callable $next    delegate next callable method.
     * @return Psr\Http\Message\ResponseInterface
     */
    public function process(ServerRequestInterface $request, DelegateInterface $delegate)
    {
        $config = require __DIR__ . "/../../config/database.php";
        $capsule = new Manager;
        $capsule->addConnection($config);
        $capsule->bootEloquent();
        $capsule->setAsGlobal();
        return Framework::router($request->withAttribute('db', $capsule->getDatabaseManager()->connection()->getPdo()));
    }
}
