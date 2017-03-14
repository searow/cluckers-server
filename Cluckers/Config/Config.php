<?php

namespace Cluckers\Config;

/**
 * Loads configuration file and provides getters for config variables.
 */
class Config {
  private $config;

  /**
   * Performs read of passed-in config file path.
   *
   * @param string $path Filepath of config file.
   */
  public function __construct($path) {
    $f = file_get_contents($path);
    $this->config = json_decode($f, true);
  }

  /**
   * Gets absolute database path from config file.
   *
   * @return string Path database file.
   */
  public function getDatabasePath() {
    // Path in config file is relative to project root, need absolute.
    $path = __DIR__ . '/../../' . $this->config['database_path'];

    return $path;
  }
}