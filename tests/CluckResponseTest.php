<?php

require_once(__DIR__ . "/../Cluckers/Request/CluckResponse.php");

use PHPUnit\Framework\TestCase as TestCase;
use Cluckers\Request\CluckResponse as CluckResponse;

class CluckResponseTest extends TestCase {

  public function testGetResponse() {
    // For this version, the output is just the raw text string for status.
    $channel = 0;
    $status = "no_bawk";
    $this->response = new CluckResponse();
    $this->response->setChannel($channel);
    $this->response->setStatus($status);
    $result = $this->response->getResponse();
    // Note that for now this is just $status, but will change once the format
    // changes to json and has more data involved.
    $this->assertEquals("no_bawk", $result);
  }
}
