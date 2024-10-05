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

  public static function respond($code, $data, $exit = true)
  {
    header('Content-Type: application/json');
    http_response_code($code);
    echo json_encode($data);
    if ($exit) exit();
  }

  public static function respondError($code, $message, $exit = true)
  {
    $json = [
      "kind"  => "error",
      "code"  => $code,
      "message" => $message
    ];
    RestApi::respond($code, $json);
    if ($exit) exit();
  }

  public static function getRequestUri()
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

  public static function getRequestPart($idx, $lowerCase = true)
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

  public static function isGet()
  {
    return $_SERVER["REQUEST_METHOD"] == "GET";
  }

  public static function isPost()
  {
    return $_SERVER["REQUEST_METHOD"] == "POST";
  }
}
