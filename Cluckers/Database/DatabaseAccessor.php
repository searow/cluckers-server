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
   * Connects to existing database using path to database.
   *
   * @param string $path Path to database file, including extension.
   * @return void.
   */
  public function connectToDatabase($path) {
    $this->dbh = new PDO("sqlite:" . $path);
  }

  /**
   * Returns cluck status of requested channel from database.
   * 
   * @param int $channel Channel to access.
   * @return bool Status of requested channel.
   */
  public function getCluckStatus($channel) {
    // Create SQL connection and query database
    // $dbh = new PDO("sqlite:" . $root_path . $db_path);
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
