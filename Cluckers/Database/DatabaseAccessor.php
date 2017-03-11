<?php

namespace Cluckers\Database;

use \PDO as PDO;

/**
 * Database accessor class. Manages all database connections and 
 * transactions.
 */
class DatabaseAccessor {
  /**
   * Returns cluck state of requested channel from database.
   * 
   * @param int $channel Channel to access.
   * @return bool State of requested channel.
   */
  public function getCluckState($channel) {
    // Get db path from project config file
    $root_path = __DIR__ . "/../../";
    $f = file_get_contents($root_path . "config/config.json");
    $config = json_decode($f, true);
    $db_path = $config["database_path"];

    // Create SQL connection and query database
    $dbh = new PDO("sqlite:" . $root_path . $db_path);
    $stmt = $dbh->prepare(
        "SELECT * FROM cluck_status WHERE channel=:channel");
    $stmt->bindParam(":channel", $channel);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    // Close database connection
    $dbh = null;

    return $result["status"];
  }

  /**
   * Sets cluck state of desired channel in database.
   *
   * Performs write to sqlite database, so this is a locking operation that
   * will block other transactions while the transaction is occurring.
   *
   * @param int $channel Channel to access.
   * @param bool $state State to set of requested channel.
   * @return void.
   */
  public function setCluckState($channel, $state) {

  }
}
