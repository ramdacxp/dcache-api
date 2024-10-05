<?php

class Database
{
  private PDO $pdo;
  private string $prefix = "";

  public function __construct(Config $config)
  {
    if ($config->DbConfig !== "") {
      $this->pdo = new PDO(
        $config->DbConfig,
        $config->DbUser,
        $config->DbPassword
      );
      $this->prefix = $config->DbPrefix;
    }
  }

  public function getNumberOfRows(): int
  {
    $stmt = $this->pdo->prepare("SELECT `id` FROM `" . $this->prefix . "data`");
    $stmt->execute();
    return $stmt->rowCount();
  }

  public function getNumberOfTokens(): int
  {
    $stmt = $this->pdo->prepare("SELECT DISTINCT `token` FROM `" . $this->prefix . "data`");
    $stmt->execute();
    return $stmt->rowCount();
  }

  public function getData(string $token): ?array
  {
    if (is_null($token) || empty($token)) return null;

    $stmt = $this->pdo->prepare("SELECT `name`, `value` FROM `" . $this->prefix . "data` WHERE (`token`=?)");
    $stmt->execute([$token]);

    if ($stmt->rowCount() < 1) return null;

    $result = [];
    $assoc = $stmt->fetchAll(PDO::FETCH_ASSOC);
    foreach ($assoc as $row) {
      $result[$row['name']] = $row['value'];
    }
    return $result;
  }

  public function deleteData(string $token): bool
  {
    if (is_null($token) || empty($token)) return false;

    $stmt = $this->pdo->prepare("DELETE FROM `" . $this->prefix . "data` WHERE (`token`=?)");
    return $stmt->execute([$token]);
  }

  public function getIdOfProperty(string $token, string $name): ?string
  {
    if (is_null($token) || empty($token)) return null;
    if (is_null($name) || empty($name)) return null;

    $stmt = $this->pdo->prepare("SELECT `id` FROM `" . $this->prefix . "data` WHERE (`token`=? AND `name`=?)");
    $stmt->execute([$token, $name]);

    $id = $stmt->fetchColumn(0);

    return $id;
  }

  public function deleteProperty(string $token, string $name): bool
  {
    if (is_null($token) || empty($token)) return false;
    if (is_null($name) || empty($name)) return false;

    $stmt = $this->pdo->prepare("DELETE FROM `" . $this->prefix . "data` WHERE (`token`=? AND `name`=?)");
    return $stmt->execute([$token, $name]);
  }


  public function insertProperty(string $token, string $name, string $value): bool
  {
    if (is_null($token) || empty($token)) return false;
    if (is_null($name) || empty($name)) return false;

    $stmt = $this->pdo->prepare("INSERT INTO `" . $this->prefix . "data` (`token`, `name`, `value`) VALUES (?, ?, ?)");
    return $stmt->execute([$token, $name, $value]);
  }



}
