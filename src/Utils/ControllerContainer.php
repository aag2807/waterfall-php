<?php

namespace Waterfall\Utils;

class ControllerContainer
{
  private $controllers = [];

  private function __construct() {}

  private static $instance = null;

  public static function getIntance(): ControllerContainer
  {
    if (static::$instance == null) {
      static::$instance = new ControllerContainer();
    }

    return static::$instance;
  }

  public function addController(mixed $controller)
  {
    array_push($this->controllers, $controller);
  }
}
