<?php

namespace BetCompare;

use BetCompare\Framework\FrontController;
use BetCompare\Framework\Request;
use BetCompare\Framework\Response;

spl_autoload_register(function ($className) {
    $className = str_replace("\\", DIRECTORY_SEPARATOR, $className);
    $file = ("src/".$className.".php");
    include $file;
});

session_start();


$frontController = new FrontController(new Request($_GET, $_POST, $_FILES, $_SERVER, $_SESSION), new Response());
$frontController->execute();
