<?php

namespace Waterfall\Utils\Decorators;

use Attribute;
use Waterfall\Utils\Enums\ReturnType;

#[Attribute(Attribute::TARGET_METHOD)]
final readonly class Put
{
  public string $route;
  public string $verb;
  public ReturnType $returnType;

  public function __construct(string $route, ReturnType $returnType = ReturnType::JSON)
  {
    $this->route = $route;
    $this->verb = "PUT";
    $this->returnType = $returnType;
  }
}
