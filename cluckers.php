<?php
/**
 * Main entry point for cluckers. To be executed by webserver when a request 
 * is made.
 */

namespace Cluckers;

require_once(__DIR__ . '/Cluckers/Config/Config.php');
require_once(__DIR__ . '/Cluckers/Database/DatabaseAccessor.php');
require_once(__DIR__ . '/Cluckers/Request/RequestHandler.php');

use Cluckers\Database\DatabaseAccessor as DatabaseAccessor;
use Cluckers\Config\Config as Config;
use Cluckers\Request\RequestHandler as RequestHandler;

// Load the config file and get the database path.
$config = new Config(__DIR__ . '/config/config.json');

// Create database accessor, request handler, and handle the actual request.
$accessor = new DatabaseAccessor($config->getDatabasePath());
$requestHandler = new RequestHandler($accessor);
$requestHandler->processRequest();
