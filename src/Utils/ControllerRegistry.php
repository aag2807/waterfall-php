<?php

namespace Waterfall\Utils;

use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use ReflectionClass;
use Waterfall\Utils\Decorators\Controller;
use Waterfall\Utils\Decorators\Delete;
use Waterfall\Utils\Decorators\Get;
use Waterfall\Utils\Decorators\Patch;
use Waterfall\Utils\Decorators\Post;
use Waterfall\Utils\Decorators\Put;
use Waterfall\Utils\Enums\ReturnType;

final class ControllerRegistry
{
  public static function registerControllers()
  {
    $classes = static::getClasses();

    foreach ($classes as $className) {
      if (!class_exists($className)) {
        continue;
      }

      $reflection = new ReflectionClass($className);
      $attributes = $reflection->getAttributes(Controller::class);

      /**
       * @var Controller
       */
      $controller_attribute = $attributes[0]->newInstance();
      $controller_route = $controller_attribute->routeBase;

      $methods = $reflection->getMethods();
      foreach ($methods as $method) {

        $methodAttributes = $method->getAttributes();
        foreach ($methodAttributes as $attribute) {
          /**
           * @var Get|Post|Patch|Put|Delete
           */
          $route = $attribute->newInstance();
          RouterConfig::registerRoute($route->verb, $controller_route, $route->route, $method);
          $is_json_header = $route->returnType == ReturnType::JSON;
          if ($is_json_header) {
            header('Content-Type: application/json');
          }

          $is_html_header = $route->returnType == ReturnType::HTML;
          if ($is_html_header) {
            header('Content-Type: text/html; charset=utf-8');
          }
        }
      }
    }
  }

  private static function getClasses()
  {
    $controllers_directory = __DIR__ . '\..\Controllers';
    $classes = [];

    $iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($controllers_directory));

    foreach ($iterator as $file) {
      if ($file->isDir()) {
        continue;
      }
      $content = file_get_contents($file->getPathname());

      $namespace = '';
      if (preg_match('/namespace\s+([^;]+);/', $content, $matches)) {
        $namespace = $matches[1] . '\\';
      }

      if (preg_match_all('/class\s+(\w+)/', $content, $matches)) {
        foreach ($matches[1] as $className) {
          $classes[] = $namespace . $className;
        }
      }
    }

    return $classes;
  }
}
