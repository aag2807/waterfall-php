<?php

namespace Waterfall\Utils\Decorators;

use Attribute;

#[Attribute(Attribute::TARGET_METHOD)]
final readonly class Put
{
  public string $route;
  public string $verb;

  public function __construct(string $route)
  {
    $this->route = $route;
    $this->verb = "PUT";
  }
}
