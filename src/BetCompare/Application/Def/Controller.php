<?php

namespace BetCompare\Application\Def;

use BetCompare\Framework\View;

class Controller {
    protected $request;
    protected $response;
    protected $view;

    public function __construct($request, $response, View $view) {
        $this->request = $request;
        $this->response = $response;
        $this->view = $view;
    }

    public function showHomePage() {
        /*$feed = file_get_contents('https://www.lequipe.fr/rss/actu_rss_Football.xml');
        //$feed = str_replace('<media:', '<', $feed);

        $rss = simplexml_load_string($feed);
        

        echo '<h1>'. $rss->channel->title . '</h1>';

foreach ($rss->channel->item as $item) {
   echo '<h2><a href="'. $item->link .'">' . $item->title . "</a></h2>";
   echo "<p>" . $item->pubDate . "</p>";
   echo "<p>" . $item->description . "</p>";
} */
        $this->view->makeHomePage(null);
    }

    public function execute($action) {
        switch ($action) {
            case "":
                $this->showHomePage();
                break;
            default:
                $this->showHomePage();
        }
    }
}
