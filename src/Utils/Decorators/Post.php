<?php

namespace Waterfall\Utils\Decorators;

use Attribute;
use Waterfall\Utils\Enums\ReturnType;

#[Attribute(Attribute::TARGET_METHOD)]
final readonly class Post
{
  public string $route;
  public string $verb;
  public ReturnType $returnType;

  public function __construct(string $route, ReturnType $returnType = ReturnType::JSON)
  {
    $this->route = $route;
    $this->verb = "POST";
    $this->returnType = $returnType;
  }
}
