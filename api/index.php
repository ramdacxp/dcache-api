<?php
require_once __DIR__ . DIRECTORY_SEPARATOR . '_rest-api.php';

// phpinfo( INFO_VARIABLES);

// INIT
// =============================================================================
$configFilename = __DIR__ . DIRECTORY_SEPARATOR . 'settings.php';
$isConfigured = file_exists($configFilename);
$request = RestApi::getRequestUri();
if ($request === "/") {
  RestApi::respondError(RestApi::CODE_400_BAD_REQUEST, "No api target selected.");
}

// CONFIG
// =============================================================================
if (RestApi::getRequestPart(1) == "config") {
  if (RestApi::isGet()) {
    RestApi::respond(RestApi::CODE_200_OK, ["isConfigured" => $isConfigured]);
  } else if (RestApi::isPost()) {
    if ($isConfigured) {
      RestApi::respondError(RestApi::CODE_403_FORBIDDEN, "API is already configured. Delete setting file from server and try again.");
    } else {
      $data = json_decode(file_get_contents("php://input"), true);
      if ($data == null) {
        RestApi::respondError(RestApi::CODE_400_BAD_REQUEST, "Request body contains no settings.");
      }
      $settings = "<" . "?php\n\$settings=[];\n";
      foreach ($data as $key => $value) {
        $settings .=  "\$settings[\"" . $key . "\"]=" . json_encode($value) . ";\n";
      }
      file_put_contents($configFilename, $settings);
      RestApi::respond(RestApi::CODE_200_OK, $data);
    }
  }
}

// NOT IMPLEMENTED
// =============================================================================
RestApi::respondError(
  RestApi::CODE_500_INTERNAL_SERVER_ERROR,
  "Request '" . $request . "' is not implemented"
);
