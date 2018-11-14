<?php

namespace BetCompare\Framework;

use BetCompare\Application\Def\Controller;
use BetCompare\Application\Images\ImageController;
use BetCompare\Framework\Request;

class Router {
    private $request;
    private $objet;
    private $action;
    private $controllerClass;

    public function __construct(Request $request) {
        $this->request = $request;
    }

    public function main() {
        $this->action = $this->request->getParamGet("a");
        $this->object = $this->request->getParamGet("o");

        switch ($this->object) {
            case "teamtracker":
                $this->controllerClass = "BetCompare\Application\Teams\TeamsController";
                break;
            default:
                $this->controllerClass = "BetCompare\Application\Teams\TeamsController";
                //BetCompare\Application\Def\Controller
        }
    }

    public function getHomeURL($team) {
        return "./".ucfirst($team);
    }

    public function getArticleURL($team,$articleID) {
        //return ".?o=teamtracker&a=article&team=$team&articleid=$articleID";
        return "./".ucfirst($team)."/article$articleID";
    }

    public function getAction() {
        return $this->action;
    }

    public function getControllerClass() {
        return $this->controllerClass;
    }
}
