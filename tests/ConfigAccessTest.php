<?php

require_once(__DIR__ . "/../Cluckers/Config/Config.php");

use PHPUnit\Framework\TestCase as TestCase;
use Cluckers\Config\Config as Config;

class ConfigAccessTest extends TestCase {
  public function testReadConfig() {
    // Use test_config.json file for sample config file and database path.
    // Note that we give the path of the config file (./test_config.json), but
    // expect everything to be reference by project root since that's the 
    // reference point for the info in the config file itself.
    $config = new Config(__DIR__ . "/test_config.json");
    $result = $config->getDatabasepath();
    $projectRoot = __DIR__ . '/../';
    $this->assertEquals(realpath($projectRoot . "./data/base/path.db"),
                        realpath($result));
  }
}