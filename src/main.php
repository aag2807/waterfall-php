<?php

namespace Waterfall;

use Ratchet\Server\IoServer;
use Ratchet\Http\HttpServer as RatchetHttpServer;
use Ratchet\WebSocket\WsServer;
use Waterfall\Utils\ControllerRegistry;
use Waterfall\Utils\RouterConfig;
use React\EventLoop\Loop;
use React\Http\HttpServer;
use React\Socket\SocketServer;
use Waterfall\Utils\WebSocketServer;

$event_loop = Loop::get();
ControllerRegistry::registerControllers();

$http_server = new HttpServer($event_loop, function ($request) {
  echo "HTTP callback triggered\n";
  var_dump(get_class($request));
  return RouterConfig::handleRequest($request);
});

$http_socket = new SocketServer("0.0.0.0:8080", [], $event_loop);
$http_server->listen($http_socket);

$ws_handler = new WebSocketServer();
$ws_server = new IoServer(
  new RatchetHttpServer(
    new WsServer($ws_handler)
  ),
  new SocketServer("0.0.0.0:8081", [], $event_loop),
  $event_loop
);

echo "HTTP Server running on http://0.0.0.0:8080\n";
echo "WebSocket Server running on ws://0.0.0.0:8081\n";

$event_loop->run();
