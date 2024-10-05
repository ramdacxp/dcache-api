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

  public static function respond($code, $data)
  {
    header('Content-Type: application/json');
    http_response_code($code);
    echo json_encode($data);
  }

  public static function respondError($code, $message)
  {
    $json = [
      "kind"  => "error",
      "code"  => $code,
      "message" => $message
    ];
    RestApi::respond($code, $json);
  }
}
