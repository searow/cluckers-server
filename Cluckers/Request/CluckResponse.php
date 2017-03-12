<?php

namespace Cluckers\Request;

/**
 * Represents the server's response to HTTP request.
 */
class CluckResponse {
  private $channel;
  private $status;
  /**
   * Gets string representation of server's response to incoming HTTP request.
   *
   * Returns raw string representation of status. No channel data included. 
   * Note that future version will change this to include other data, probably
   * in json format, but for now, just the status text will do. 
   *
   * @return string HTTP response to send back to client.
   */
  public function getResponse() {
    return $this->status;
  }

  /**
   * Sets channel data of response.
   * @param int $channel Channel that was used.
   * @return void.
   */
  public function setChannel($channel) {
    $this->channel = $channel;
  }

  /**
   * Sets status data of response.
   * @param string $status Status of the channel.
   * @return void.
   */
  public function setStatus($status) {
    $this->status = $status;
  }
}