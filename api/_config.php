<?php

class Config
{
  public $ConfigFilename = __DIR__ . DIRECTORY_SEPARATOR . 'settings.php';

  public $DbConfig = "";
  public $DbUser = "";
  public $DbPassword = "";
  public $DbPrefix = "";

  public function isConfigured(): bool
  {
    return file_exists($this->ConfigFilename);
  }

  public function canBeConfigured(): bool
  {
    return is_writeable(__DIR__);
  }

  public function loadIfConfigured(): bool
  {
    if (!$this->isConfigured()) return false;
    require_once $this->ConfigFilename;
    $this->DbConfig = $settings['database'];
    $this->DbUser = $settings['user'];
    $this->DbPassword = $settings['password'];
    $this->DbPrefix = $settings['prefix'];
    return true;
  }

  public function saveSettings($data): bool
  {
    if ($this->isConfigured()) return false;
    if (!$this->canBeConfigured()) return false;
    if ($data == null) return false;

    $settings = "<" . "?php\n\$settings=[];\n";
    foreach ($data as $key => $value) {
      $settings .=  "\$settings[\"" . $key . "\"]=" . json_encode($value) . ";\n";
    }

    file_put_contents($this->ConfigFilename, $settings);
    return true;
  }
}
