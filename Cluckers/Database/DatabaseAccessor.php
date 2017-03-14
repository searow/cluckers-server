<?php

namespace Cluckers\Database;

use \PDO as PDO;

/**
 * Database accessor class. Manages all database connections and 
 * transactions.
 */
class DatabaseAccessor {
  private $dbh;

  /**
   * Gets database path from config and opens the database.
   *
   * @param string $path Path to database file.
   * @return void.
   */
  public function __construct($path) {
    // TODO(searow): add try catch for when database/path is inaccessible, 
    //               or doesn't exist.
    $this->dbh = self::connectToDatabase($path);
  }

  /**
   * Connects to existing database using path to database.
   *
   * @param string $path Path to database file, including extension.
   * @return void.
   */
  private static function connectToDatabase($path) {
    return new PDO("sqlite:" . $path);
  }

  /**
   * Returns cluck status of requested channel from database.
   * 
   * @param int $channel Channel to access.
   * @return bool Status of requested channel.
   */
  public function getCluckStatus($channel) {
    // Create SQL connection and query database
    $stmt = $this->dbh->prepare(
        "SELECT * FROM cluck_status WHERE channel=:channel");
    $stmt->bindParam(":channel", $channel);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    return $result["status"];
  }

  /**
   * Sets cluck status of desired channel in database.
   *
   * Performs write to sqlite database, so this is a locking operation that
   * will block other transactions while the transaction is occurring.
   *
   * @param int $channel Channel to access.
   * @param string $status Status to set of requested channel.
   * @return void.
   */
  public function setCluckStatus($channel, $status) {
    $stmt = $this->dbh->prepare("
        UPDATE cluck_status 
               SET status=:status
             WHERE channel=:channel;");
    $stmt->bindParam(":channel", $channel);
    $stmt->bindParam(":status", $status);
    $stmt->execute();
  }
}
