<?php

class RestApi
{
  const CODE_200_OK = 200;
  const CODE_201_CREATED = 201;
  const CODE_400_BAD_REQUEST = 400;
  const CODE_401_UNAUTHORIZED = 401;
  const CODE_403_FORBIDDEN = 403;
  const CODE_404_NOT_FOUND = 404;
  const CODE_500_INTERNAL_SERVER_ERROR = 500;

  public static function respond($code, $data, $exit = true): void
  {
    header('Content-Type: application/json');
    http_response_code($code);
    echo json_encode($data);
    if ($exit) exit();
  }

  public static function respondError($code, $message, $exit = true): void
  {
    $json = [
      "kind"  => "error",
      "code"  => $code,
      "message" => $message
    ];
    RestApi::respond($code, $json);
    if ($exit) exit();
  }

  public static function getRequestUri(): string
  {
    $request = "";

    if (is_string($_SERVER["REQUEST_URI"])) {
      $request = $_SERVER["REQUEST_URI"];
    }

    if (!str_ends_with($request, "/")) {
      $request .= "/";
    }

    return $request;
  }

  public static function getRequestPart($idx, $lowerCase = true): string
  {
    $result = "";

    $segments = explode("/", RestApi::getRequestUri());
    if ($idx >= 0 && $idx < count($segments)) {
      $result = $segments[$idx];
    }

    if ($lowerCase) {
      $result = strtolower($result);
    }

    return $result;
  }

  public static function isGet(): bool
  {
    return $_SERVER["REQUEST_METHOD"] == "GET";
  }


  public static function isPost(): bool
  {
    return $_SERVER["REQUEST_METHOD"] == "POST";
  }

  public static function validateToken($token)
  {
    if (!isset($token) || is_null($token) || empty($token)) {
      RestApi::respondError(RestApi::CODE_400_BAD_REQUEST, "No token given.");
    }

    if (strlen($token) < 8) {
      RestApi::respondError(RestApi::CODE_400_BAD_REQUEST, "Token is too short. Must be at least 8 characters long.");
    }

    if (!preg_match('/^[a-zA-Z0-9\.-]+$/', $token)) {
      RestApi::respondError(RestApi::CODE_400_BAD_REQUEST, "Token contains invalid characters. Only letters, numbers, dots and dashes are allowed.");
    }
  }
}
