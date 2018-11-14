<?php

namespace BetCompare\Application\Teams;
use duzun\hQuery;
include_once 'hQueryLib/hquery.php';


/* Represent a team */
class Team {
    private $name;
    private $otherNames;
    private $rss;
    private $newsTitles;
    private $odds;

    public function __construct($name, $otherNames) {
        $this->name = $name;
        $this->otherNames = $otherNames;
        $this->newsTitles = array();


        $feed = file_get_contents('https://www.lequipe.fr/rss/actu_rss_Football.xml');
        $this->rss = simplexml_load_string($feed);
        //var_dump($this->rss);

        $dirNews = dirname(__DIR__)."/Teams/News/".$this->name.".json";
        foreach ($this->rss->channel->item as $item) {
            //var_dump($this->title);
            foreach($this->otherNames as $n) {
                if(strpos($item->title,$n) !== false) {
                    $token = array();
                    $token['title'] = (string) $item->title;
                    $token['date'] = (string) $item->pubDate;
                    $token['description'] = (string) $item->description;
                    $token['link'] = (string) $item->link;
                    array_push($this->newsTitles,$token);
                    break;
                }
            }
        }
        $this->writeJson($this->newsTitles,$dirNews);
        $this->newsTitles = unserialize(file_get_contents($dirNews));

        //$this->updateData();

        //$this->csvFileToArray();
        //$this->getOddsHistoric("Nice");

        $this->getNextGames();
    }

    //scrap data of ONE article
    public function getContentArticle($article) {
        $doc = hQuery::fromUrl($article['link'], ['Accept' => 'text/html,application/xhtml+xml;q=0.9,*/*;q=0.8']);
        
        $content = $doc->find('.article__paragraphe');
        if($content) {
            $content = $content->text();
        }

        return $content;
    }

    private function writeJson($data, $dirname) {
        if(!file_exists($dirname)) {
            file_put_contents($dirname, serialize($data));
        }
        else {
            $dataCompare = unserialize(file_get_contents($dirname));

            foreach ($data as $key => $token) {
                $i = 0;
                foreach ($dataCompare as $tokenCompare) {
                    if(!in_array($token['title'], $tokenCompare)) {
                        $i = $i + 1;
                    }
                }
                if($i === sizeof($dataCompare)) {
                    $token["content"] = $this->getContentArticle($token);
                    array_unshift($dataCompare,$token);
                    if(sizeof($dataCompare) > 15) {
                        unset($dataCompare[15]);
                    }
                }
            }
            file_put_contents($dirname, serialize($dataCompare));
        }
    }

    //get the content of all articles from the cache files
    public function getContentAllArticles() {
        $res = '';

        $dirname = dirname(__DIR__)."/Teams/News/".$this->name.".json";
        $data = unserialize(file_get_contents($dirname));

        foreach ($data as $article) {
            $res .= $article["content"];
        }

        return $res;
    }

    //update data to scrap content and cache it
    public function updateData() {
        $dirname = dirname(__DIR__)."/Teams/News/".$this->name.".json";

        $dataCompare = unserialize(file_get_contents($dirname));

        foreach ($dataCompare as $key => $tokenCompare) {
            $content = $this->getContentArticle($tokenCompare);
            $dataCompare[$key]["content"] = $content;
        }

        file_put_contents($dirname, serialize($dataCompare));
    }

    public function csvFileToArray($year) {
        $dirname = dirname(__DIR__)."/Teams/Games/ligue1_$year.csv";

        $fileHandle = fopen($dirname, 'r');
        while (!feof($fileHandle) ) {
            $csvArray[] = fgetcsv($fileHandle, 1024);
        }
        fclose($fileHandle);

        return $csvArray;
    }

    public function getOddsHistoric($opponent) {
        $array1 = $this->csvFileToArray("2015");
        $array2 = $this->csvFileToArray("2016");
        $array3 = $this->csvFileToArray("2017");
        $csvArray = array_merge($array1, $array2);
        $csvArray = array_merge($csvArray, $array3);

        if($this->name === "Paris") {
            $teamName = "Paris SG";
        } 
        else if($this->name === "Saint-etienne") {
            $teamName = "St Etienne";
        }
        else {
            $teamName = $this->name;
        }

        if($opponent === "Saint-Etienne") {
            $opponent = "St Etienne";
        }

        $resArray = array();
        foreach ($csvArray as $value) {
            if ((strtolower($value[2]) === strtolower($opponent) 
                && strtolower($value[3]) === strtolower($teamName)) ||
                (strtolower($value[3]) === strtolower($opponent)
                && strtolower($value[2]) === strtolower($teamName))) {
                $resArray[] = $value;
            }
        }

        return $resArray;
    }

    public function getNextGames() {
        //var_dump($this->name);
        if($this->name === "Saint-etienne") {
            $teamName = "Saint-Etienne";
        }
        else {
            $teamName = $this->name;
        }   

        $doc = hQuery::fromUrl("http://www.cotes.fr/football/France-Ligue-1-ed3", ['Accept' => 'text/html,application/xhtml+xml;q=0.9,*/*;q=0.8']);
        
        $content = $doc->find('.bettable tr');

        $gameOdds = array();

        foreach ($content as $key => $value) {
            if(!$value->hasClass('trout')) {

                $gameName = $value->find('.maincol h2')->text();
                $gameDate = str_replace($value->find('.maincol div:last-child')->text(), '', $value->find('.maincol')->text());
                $gameDate = str_replace($gameName, '', $gameDate);

                
                $bookmaker = $value->nextElementSibling();
                $tempArray = array();
                if(strpos($gameName, $teamName)) {
                    while (!is_null($bookmaker->find('.bet'))) {
                        $pieces = explode(' ',$bookmaker->title);
                        $nameBookmaker = array_pop($pieces);

                        $odds = array_values(array_filter(explode(' ',trim($bookmaker->find('.bet')->text()))));

                        $odds = array_values(array_filter(explode(' ',trim($bookmaker->find('.bet')->text()))));


                        $home = $odds[0];
                        $draw = $odds[2];
                        $away = $odds[4];

                        $oddsArray = array(
                        "home"=>trim($home),
                        "draw"=>trim($draw),
                        "away"=>trim($away));

                        $tempArray[$nameBookmaker] = $oddsArray;

                        $bookmaker = $bookmaker->nextElementSibling();
                        if(is_null($bookmaker)) {
                            break;
                        }
                    }   

                    $groupArray = array();
                    $groupArray["details"]["name"] = trim($gameName);
                    $groupArray["details"]["date"] = trim($gameDate);
                    
                    $groupArray["odds"] = $tempArray;

                    array_push($gameOdds, $groupArray);
                }
            }
        }

        //var_dump($gameOdds[1]);
        return $gameOdds;
    }

    public function getName() {
        return $this->name;
    }

    public function getOtherNames() {
        return $this->otherNames();
    }

    public function getRSS() {
        return $this->rss();
    }

    public function getNewsTitles() {
        return $this->newsTitles;
    }

    public function getNewsArticle($articleID) {
        return $this->newsTitles[$articleID];
    }
}
