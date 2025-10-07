<?php

namespace Waterfall\Controllers;

use Waterfall\Utils\Decorators\Controller;
use Waterfall\Utils\Decorators\Get;
use Waterfall\Utils\Enums\ReturnType;
use Waterfall\Utils\HttpResponse;

#[Controller("/home")]
class HomeController
{
  #[Get("/")]
  public function index()
  {
    return HttpResponse::ok(["info" => "Hello World"]);
  }

  #[Get("/home", ReturnType::HTML)]
  public function Home()
  {
    return "<h1>HOME ROUTE</h1>";
  }
}
