<?php

namespace Waterfall\Utils;

use ReflectionMethod;

final class RouterConfig
{
  /**
   * @var array<string, ReflectionMethod>
   */
  private static $routes = [];

  public static function registerRoute(string $verb, string $base, string $route, ReflectionMethod $function)
  {
    $fullRoute = rtrim('/' . ltrim($base, '/') . '/' . ltrim($route, '/'), '/') ?: '/';
    static::$routes[$verb][$fullRoute] = $function;
  }

  public static function listen()
  {
    ob_start();

    $method = $_SERVER['REQUEST_METHOD'];
    $route = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    $route = rtrim($route, '/') ?: '/';

    /**
     * @var ReflectionMethod
     */
    $cb = static::$routes[$method][$route] ?? null;

    if ($cb === null) {
      http_response_code(404);
      echo json_encode(["error" => "404 not found"]);
      return;
    }

    $controllerClass = $cb->getDeclaringClass()->getName();
    $controllerInstance = new $controllerClass();

    $result = $cb->invoke($controllerInstance);
    if ($result !== null) {
      echo $result;
    }

    $content = ob_get_clean();
    echo $content;
  }
}
