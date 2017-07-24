<?php

require_once __DIR__ . '/auth.php';

return Fermi\Framework::middleware([
    App\Middleware\Database::class,
    App\Middleware\Router::class,
]);
