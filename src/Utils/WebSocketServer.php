<?php

namespace Waterfall\Utils;

use Ratchet\ConnectionInterface;
use Ratchet\MessageComponentInterface;

final class WebSocketServer implements MessageComponentInterface
{
  protected $clients;

  public function __construct()
  {
    $this->clients = new \SplObjectStorage;
  }

  public function onOpen(ConnectionInterface $conn)
  {
    $this->clients->attach($conn);
    echo "New connection: {$conn->resourceId}\n";
  }

  public function onMessage(ConnectionInterface $from, $msg)
  {
    echo "Message from {$from->resourceId}: {$msg}\n";

    foreach ($this->clients as $client) {
      $client->send($msg);
    }
  }

  public function onClose(ConnectionInterface $conn)
  {
    $this->clients->detach($conn);
    echo "Connection closed: {$conn->resourceId}\n";
  }

  public function onError(ConnectionInterface $conn, \Exception $e)
  {
    echo "Error: {$e->getMessage()}\n";
    $conn->close();
  }
}
