<?php

require_once(__DIR__ . "/../Cluckers/Config/Config.php");

use PHPUnit\Framework\TestCase as TestCase;
use Cluckers\Config\Config as Config;

class ConfigAccessTest extends TestCase {
  public function testReadConfig() {
    // Use test_config.json file for sample config file and database path.
    $config = new Config(__DIR__ . "/test_config.json");
    $result = $config->getDatabasepath();
    $this->assertEquals("/some/test/path", $result);
  }
}