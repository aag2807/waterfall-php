<?php

namespace Waterfall\Utils\Decorators;

use Attribute;

#[Attribute(Attribute::TARGET_METHOD)]
final readonly class Patch
{
  public string $route;

  public function __construct(string $route)
  {
    $this->route = $route;
  }
}
