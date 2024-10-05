<?php
require_once __DIR__ . DIRECTORY_SEPARATOR . '_rest-api.php';

$request = "";
if (is_string($_SERVER['REQUEST_URI'])) {
  $request = $_SERVER['REQUEST_URI'];
}

RestApi::respondError(
  RestApi::CODE_500_INTERNAL_SERVER_ERROR,
  "Request '" . $request . "' is not implemented"
);

// phpinfo( INFO_VARIABLES);