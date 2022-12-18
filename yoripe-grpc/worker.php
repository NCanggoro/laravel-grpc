<?php

use Illuminate\Contracts\Console\Kernel;
use \Service\UserServiceInterface;
use \Service\AuthServiceInterface;
use \App\Services\UserService;
use \App\Services\AuthService;
use Spiral\RoadRunner\Worker;
use \Spiral\GRPC\Server;


require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(Kernel::class)->bootstrap();

$server = $app->make(\Spiral\GRPC\Server::class);
$server->registerService(UserServiceInterface::class, new UserService());
$server->registerService(AuthServiceInterface::class, new AuthService());
$worker = new  Spiral\RoadRunner\Worker(new Spiral\Goridge\StreamRelay(STDIN, STDOUT));
$server->serve($worker);