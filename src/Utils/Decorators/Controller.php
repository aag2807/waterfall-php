<?php

namespace Waterfall\Utils\Decorators;

use Attribute;

use Waterfall\Utils\ControllerContainer;

#[Attribute(Attribute::TARGET_CLASS)]
final readonly class Controller
{
  public string $routeBase;

  public function __construct(string $routeBase)
  {
    $this->routeBase = $routeBase;
  }
}
