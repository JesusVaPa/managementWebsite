<?php
declare(strict_types=1);


require_once "private/helpers/init.php";

use Application\Application;

Debug::$DEBUG_MODE = false;

// TODO @Students You should create your own 'application'-style class and use it here
// You can base yourself on my own 'Teacher\Examples\Application' class;
// in it you can use my 'Teacher\GivenCode\Services\InternalRouter' class wich is given code.
$application = new Application();
$application->run();

