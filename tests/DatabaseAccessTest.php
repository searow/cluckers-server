<?php

require_once(__DIR__ . "/../Cluckers/Database/DatabaseAccessor.php");
require_once(__DIR__ . "/../Cluckers/Config/Config.php");

use \PDO as PDO;
use PHPUnit\Framework\TestCase as TestCase;
use Cluckers\Config\Config as Config;
use Cluckers\Database\DatabaseAccessor as DatabaseAccessor;

class DatabaseAccessTest extends TestCase {
  protected $accessor;
  private static $testDbFilePath = __DIR__ . "/test_delete_me.db";

  protected function setUp() {
    // Previous fails can leave test db existing, so we should look for it and 
    // delete it if it exists.
    if (is_writable(self::$testDbFilePath)) {
      unlink(self::$testDbFilePath);
    }

    // Test database is just in-memory database. Prepare it by creating the
    // cluck table and inserting base channel to access. Make sure the test
    // database is deleted in tearDown().
    $dbh = new PDO("sqlite:" . self::$testDbFilePath);
    $stmt = $dbh->prepare(
        "CREATE TABLE cluck_status (channel INT, status TEXT);");
    $stmt->execute();
    $stmt = $dbh->prepare(
        "INSERT INTO cluck_status (channel, status)
              VALUES (0, 'no_bawk');");
    $stmt->execute();

    $dbh = null;

    // Create accessor object and initialize it with the previously created
    // database to access.
    $this->accessor = new DatabaseAccessor(self::$testDbFilePath);
  }

  public function testCluckStatusReadAndWrite() {
    // In-memory database is empty to begin with. Test set and get by querying
    // the data twice.
    // There are 3 statuses to test: no_bawk, ready_bawk, response_bawk.
    $channel = 0;

    // Initial status is set by setUp fixture.
    $result = $this->accessor->getCluckStatus($channel);

    // Set status to no_bawk.
    $this->accessor->setCluckStatus($channel, "no_bawk");
    $result = $this->accessor->getCluckStatus($channel);
    $this->assertEquals("no_bawk", $result);

    // Set status to ready_bawk.
    $this->accessor->setCluckStatus($channel, "ready_bawk");
    $result = $this->accessor->getCluckStatus($channel);
    $this->assertEquals("ready_bawk", $result);
  }

  protected function tearDown() {
    // Make sure to remove reference here so that the file becomes writeable.
    $this->accessor = null;
    if (is_writable(self::$testDbFilePath)) {
      unlink(self::$testDbFilePath);
    } else {
      print("Unable to delete file. Delete db file in tests/ dir.");
    }
  }
}