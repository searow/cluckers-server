<?php

require_once(__DIR__ . "/../Cluckers/Request/RequestHandler.php");

use PHPUnit\Framework\TestCase as TestCase;
use Cluckers\Request\RequestHandler as RequestHandler;
use Cluckers\Database\DatabaseAccessor as DatabaseAccessor;

class RequestHandlerTest extends TestCase {
  protected $mockDbAccessor;

  protected function setUp() {
    // Mock database to query for 
    $this->mockDbAccessor = $this->createMock(DatabaseAccessor::class);
    $this->mockDbAccessor->method('getCluckStatus')
                         ->willReturn('no_bawk');
  }

  public function testHandleReadCluckGetRequest() {
    // CGI variables are added to existing $_SERVER to emulate same things
    // that we expect apache to handle so command line tests can run properly.
    $_SERVER['REQUEST_URI'] = '/cluckers/v1/cluck/0';
    $_SERVER['REQUEST_METHOD'] = 'GET';
    $handler = new RequestHandler($this->mockDbAccessor);
    $handler->processRequest();
    $this->expectOutputString('no_bawk');
  }

  // Figure out how to check PUT request result since putHandler() doesn't 
  // return anything.
  // public function testHandleSetCluckPostRequest() {
  //   // CGI variables are added to existing $_SERVER to emulate same things
  //   // that we expect apache to handle so command line tests can run properly.
  //   $_SERVER['REQUEST_URI'] = '/cluckers/v1/cluck/0';
  //   $_SERVER['REQUEST_METHOD'] = 'PUT';
  //   $handler = new RequestHandler($this->mockDbAccessor);
  //   $handler->processRequest();
  // }
}