<?php

namespace Waterfall\Utils;

use React\Http\Message\Response as ReactResponse;

final class HttpResponse
{
  public static function ok($data = '', string $contentType = 'application/json')
  {
    return new ReactResponse(200, ['Content-Type' => $contentType], self::formatBody(
      $data,
      $contentType
    ));
  }

  public static function created($data = '', string $contentType = 'application/json')
  {
    return new ReactResponse(201, ['Content-Type' => $contentType], self::formatBody(
      $data,
      $contentType
    ));
  }

  public static function accepted($data = '', string $contentType = 'application/json')
  {
    return new ReactResponse(202, ['Content-Type' => $contentType], self::formatBody(
      $data,
      $contentType
    ));
  }

  public static function noContent()
  {
    return new ReactResponse(204);
  }

  public static function badRequest($message = 'Bad Request')
  {
    return new ReactResponse(400, ['Content-Type' => 'application/json'], json_encode(['error' =>
    $message]));
  }

  public static function unauthorized($message = 'Unauthorized')
  {
    return new ReactResponse(401, ['Content-Type' => 'application/json'], json_encode(['error' =>
    $message]));
  }

  public static function forbidden($message = 'Forbidden')
  {
    return new ReactResponse(403, ['Content-Type' => 'application/json'], json_encode(['error' =>
    $message]));
  }

  public static function notFound($message = 'Not Found')
  {
    return new ReactResponse(404, ['Content-Type' => 'application/json'], json_encode(['error' =>
    $message]));
  }

  public static function serverError($message = 'Internal Server Error')
  {
    return new ReactResponse(500, ['Content-Type' => 'application/json'], json_encode(['error' =>
    $message]));
  }

  public static function html(string $content)
  {
    return new ReactResponse(200, ['Content-Type' => 'text/html; charset=utf-8'], $content);
  }

  public static function json($data, int $status = 200)
  {
    return new ReactResponse($status, ['Content-Type' => 'application/json'], json_encode($data));
  }

  private static function formatBody($data, string $contentType)
  {
    if ($contentType === 'application/json') {
      return is_string($data) ? $data : json_encode($data);
    }
    return (string) $data;
  }
}
