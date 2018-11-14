<?php

namespace BetCompare\Framework;

use BetCompare\Framework\Router;
use BetCompare\Framework\Request;
use BetCompare\Framework\Response;
use BetCompare\Application\Def\Controller;
use BetCompare\Application\Teams\TeamsController;

class FrontController {
    protected $request;
    protected $response;

    public function __construct(Request $request, Response $response) {
        $this->request = $request;
        $this->response = $response;
    }

    public function execute() {
        $router = new Router($this->request);
        $router->main();

        $controllerClass = $router->getControllerClass();
        $action = $router->getAction();
        $articleID = $this->request->getParamGet("articleid");
        $team = $this->request->getParamGet("team");

        $view = new View($router);
        
        $test = new TeamsController($this->request, $this->response, $view);
        $controller = new $controllerClass($this->request, $this->response, $view);

        $controller->execute($action,$team,$articleID);
        $this->response->sendResponse($view);
    }
}
