<?php
/**
 * Main entry point for cluckers. To be executed by webserver when a request 
 * is made.
 */

namespace Cluckers;

use Cluckers\Database\DatabaseAccessor;

require_once __DIR__ . "/Cluckers/Database/DatabaseAccessor.php";

$db = new DatabaseAccessor();

print_r($db->getCluckState(1));
