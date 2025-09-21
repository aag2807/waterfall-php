<?php

namespace Waterfall\Utils;

use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use ReflectionClass;
use Waterfall\Utils\Decorators\Controller;

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

      if (!empty($attributes)) {
        $attributes[0]->newInstance();
      }
    }
  }

  private static function getClasses()
  {
    $controllers_directory = __DIR__ . '\..\Controllers';
    $classes = [];

    $iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($controllers_directory));

    foreach ($iterator as $file) {
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
