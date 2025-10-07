<?php

namespace Waterfall\Utils;

use ReflectionMethod;
use React\Http\Message\Response;

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

  public static function handleRequest($request)
  {
    try {
      $method = $request->getMethod();
      $route = rtrim($request->getUri()->getPath(), '/') ?: '/';

      echo "[{$method}] {$route}\n";

      /**
       * @var ReflectionMethod
       */
      $cb = static::$routes[$method][$route] ?? null;

      if ($cb === null) {
        echo "404 - Route not found\n";
        return new Response(
          404,
          ['Content-Type' => 'application/json'],
          json_encode(["error" => "404 not found", "route" => $route])
        );
      }

      $controllerClass = $cb->getDeclaringClass()->getName();
      $controllerInstance = new $controllerClass();

      $result = $cb->invoke($controllerInstance);

      // If controller returns a Response object, return it directly
      if ($result instanceof Response) {
        echo "200 - Success (Response object)\n";
        return $result;
      }

      // Otherwise, wrap the result in a Response
      // Determine content type from route attributes
      $attributes = $cb->getAttributes();
      $contentType = 'text/plain';

      foreach ($attributes as $attr) {
        $instance = $attr->newInstance();
        if (property_exists($instance, 'returnType') && $instance->returnType !== null) {
          $contentType = $instance->returnType == \Waterfall\Utils\Enums\ReturnType::JSON
            ? 'application/json'
            : 'text/html; charset=utf-8';
          break;
        }
      }

      echo "200 - Success (wrapped)\n";
      return new Response(
        200,
        ['Content-Type' => $contentType],
        $result ?? ''
      );
    } catch (\Exception $e) {
      echo "Error: {$e->getMessage()}\n";
      return new Response(
        500,
        ['Content-Type' => 'application/json'],
        json_encode(["error" => "Internal server error", "message" => $e->getMessage()])
      );
    }
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
