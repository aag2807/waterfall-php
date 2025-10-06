<?php

namespace Waterfall\Controllers;

use Waterfall\Utils\Decorators\Controller;
use Waterfall\Utils\Decorators\Get;
use Waterfall\Utils\Enums\ReturnType;

#[Controller("/home")]
class HomeController
{
  #[Get("/")]
  public function index()
  {
    return "route";
  }

  #[Get("/home", ReturnType::HTML)]
  public function Home()
  {
    return "<h1>HOME ROUTE</h1>";
  }
}
