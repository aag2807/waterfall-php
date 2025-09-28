<?php

namespace Waterfall\Controllers;

use Waterfall\Utils\Decorators\Controller;
use Waterfall\Utils\Decorators\Get;

#[Controller("/home")]
class HomeController {


  #[Get("/")]
  public function index() {
    return "route";
  }
}
