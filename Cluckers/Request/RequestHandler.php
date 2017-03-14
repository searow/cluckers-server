<?php

namespace Cluckers\Request;

require_once(__DIR__ . '/../Database/DatabaseAccessor.php');
require_once(__DIR__ . '/CluckResponse.php');

use Cluckers\Database\DatabaseAccessor as DatabaseAccessor;
use Cluckers\Request\CluckResponse as CluckResponse;

/**
 * Handles and processes HTTP request. Main external-facing class.
 */
class RequestHandler {
  private $databaseAccessor;

  /**
   * Saves database accessor object.
   *
   * @param DatabaseAccessor $databaseAccessor Database access object.
   */
  public function __construct($databaseAccessor) {
    $this->databaseAccessor = $databaseAccessor;
  }

  /**
   * Processes incoming HTTP request.
   *
   * @return void.
   */
  public function processRequest() {
    if ($_SERVER['REQUEST_METHOD'] === "GET") {
      $this->getHandler();
    } else if ($_SERVER['REQUEST_METHOD'] === "PUT") {
      $this->putHandler();
    }
  }

  /**
   * Handles PUT request.
   *
   * PUT request updates cluck status in the database. Will modify the 
   * resulting HTTP response header to 204 or 400 depending on success/failure.
   * Performs a write to the database.
   *
   * @return void.
   */
  private function putHandler() {
    $req = self::decodeUriToData($_SERVER['REQUEST_URI']);
    $channel = $req['channel'];
    $body = file_get_contents('php://input');

    // For this version, only accept 3 different strings. Anything else gets
    // thrown away and result in HTTP 400.
    if ($body == 'ready_bawk'
        or $body == 'no_bawk' 
        or $body == 'response_bawk') {
      $this->databaseAccessor->setCluckStatus($channel, $body);
      http_response_code(204);
    } else {
      http_response_code(400);
    }
  }

  /**
   * Handles GET request.
   *
   * GET request just checks database data for cluck status. Will print to
   * HTTP response body here for the actual cluck status response.
   *
   * @return void.
   */
  private function getHandler() {
    $req = self::decodeUriToData($_SERVER['REQUEST_URI']);
    $channel = $req['channel'];
    // Ask database for cluck status.
    $status = $this->databaseAccessor->getCluckStatus($channel);
    // Prepare and send HTTP response
    $response = new CluckResponse();
    $response->setChannel($channel);
    $response->setStatus($status);
    print($response->getResponse());
  }

  /**
   * Helper function to decode URI into api version and channel number.
   *
   * @return $data $uriData[] Array of $apiVersion and $channel.
   */
  private static function decodeUriToData($uri) {
    preg_match("<^/cluckers/v(\d+)/cluck/(\d+)$>", $uri, $matches);
    $apiVersion = (int)$matches[1];
    $channel = (int)$matches[2];

    $data = [
        "apiVersion" => $apiVersion,
        "channel" => $channel,
    ];

    return $data;
  }
}
