<?php
require_once __DIR__ . DIRECTORY_SEPARATOR . '_rest-api.php';
require_once __DIR__ . DIRECTORY_SEPARATOR . '_config.php';
require_once __DIR__ . DIRECTORY_SEPARATOR . '_database.php';

// phpinfo( INFO_VARIABLES);

// INIT
// =============================================================================
$request = RestApi::getRequestUri();
if ($request === "/") {
  RestApi::respondError(RestApi::CODE_400_BAD_REQUEST, "No api target selected.");
}

$config = new Config();
$config->loadIfConfigured();

// CONFIG
// =============================================================================
if (RestApi::getRequestPart(1) == "config") {
  if (RestApi::isGet()) {
    RestApi::respond(
      RestApi::CODE_200_OK,
      [
        "isConfigured" => $config->isConfigured(),
        "canBeConfigured" => $config->canBeConfigured()
      ]
    );
  } else if (RestApi::isPost()) {
    if ($config->isConfigured()) {
      RestApi::respondError(RestApi::CODE_403_FORBIDDEN, "API is already configured. Delete setting file from server and try again.");
    } else {
      $data = json_decode(file_get_contents("php://input"), true);
      if ($config->saveSettings($data)) {
        RestApi::respond(RestApi::CODE_200_OK, $data);
      } else {
        RestApi::respondError(RestApi::CODE_500_INTERNAL_SERVER_ERROR, "Could not save settings.");
      }
    }
  }
}

// ENSURE CONFIGURATION & INIT DB
// =============================================================================
if (!$config->isConfigured()) {
  RestApi::respondError(RestApi::CODE_500_INTERNAL_SERVER_ERROR, "Database is not configured.");
}
$db = new Database($config);

// data/{token}
// =============================================================================
if (RestApi::getRequestPart(1) == "data") {
  $token = RestApi::getRequestPart(2, false);
  RestApi::validateToken($token);

  if (RestApi::isGet()) {
    $db = new Database($config);
    $data = $db->getData($token);
    if ($data !== null) {
      RestApi::respond(RestApi::CODE_200_OK, $data);
    } else {
      RestApi::respondError(RestApi::CODE_404_NOT_FOUND, "Dataset not found.");
    }
  }
}


// NOT IMPLEMENTED
// =============================================================================
RestApi::respondError(
  RestApi::CODE_500_INTERNAL_SERVER_ERROR,
  "Request '" . $request . "' is not implemented"
);
