<?php

namespace BetCompare\Application\Teams;

use BetCompare\Framework\Request;
use BetCompare\Framework\Response;
use BetCompare\Framework\View;
use BetCompare\Application\Teams\TeamStorage;
use duzun\hQuery;
include_once 'hQueryLib/hquery.php';


class TeamsController {
    protected $request;
    protected $response;
    protected $view;
    private $teamStorage;

    public function __construct(Request $request, Response $response, View $view) {
        $this->request = $request;
        $this->response = $response;
        $this->view = $view;

        //$this->teamStorage = new TeamStorageStub();
    }

    public function showHomePage($name) {
        //$team = $this->teamStorage->readName($name);
        $team = $this->getTeam($name);
        $this->view->makeHomePage($team);
    }

    public function showArticlePage($name,$articleID) {
        $team = $this->getTeam($name);
        $article = $team->getNewsArticle($articleID);

        $this->view->makeArticlePage($team,$article);
    }

    public function getTeam($name) {
        if($name === "Paris") {
            return new Team("Paris",["PSG","Paris Saint-Germain F.C.","Paris Saint-Germain","Paris"]);
        }
        else {
            return new Team($name,array($name));
        }
    }

    public function execute($action,$team,$articleID) {
        switch ($action) {
            case "article":
                $this->showArticlePage($team,$articleID);
                break;
            default:
                $this->showHomePage($team);
                break;
        }
    }
}
