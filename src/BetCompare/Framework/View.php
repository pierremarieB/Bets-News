<?php

namespace BetCompare\Framework;

class View {
    protected $router;
    protected $log_form;

    public function __construct(Router $router) {
        $this->router = $router;
        $this->parts = array();
        $this->parts["title"] = null;
        $this->parts["content"] = null;
    }

    public function addPart($name, $content) {
        $this->parts[$name] = $content;
    }

    public function render() {
        if ($this->parts["title"] === null || $this->parts["content"] === null) {
            $this->makeUnexpectedErrorPage();
        }
        $parts = $this->parts;
        include(__DIR__.DIRECTORY_SEPARATOR."template.php");
    }

    public static function htmlesc($str) {
    	return htmlspecialchars($str, ENT_QUOTES | ENT_SUBSTITUTE | ENT_HTML5, 'UTF-8');
    }

    /******************************************************************************/
    /* Pages generation                                                           */
    /******************************************************************************/

    public function makeHomePage($team) {
        $teamName = $team->getName();
        $newsTitles = $team->getNewsTitles();

        $this->parts["title"] = "Welcome on Football Team Tracker!";
        $this->parts["content"] = "<p id='currentTeam'>You're currently following $teamName. You can pick an other team in the menu above.</p>";
        $this->parts["content"] .= "<div id='teamNews'>";
        $this->parts["content"] .= "<div id='news'>";
        $this->parts["content"] .= "<h2 class='subtitles'>Recent news of $teamName:</h2>";

        //WORDCLOUD
        $this->parts["content"] .= '<div id="homeWordcloud">';
        $this->parts["content"] .= '<script src="js/d3-wordcloud-master/lib/d3/d3.js"></script>';
        $this->parts["content"] .= '<script src="js/d3-wordcloud-master/lib/d3/d3.layout.cloud.js"></script>';
        $this->parts["content"] .= '<script src="js/d3-wordcloud-master/d3.wordcloud.js"></script>';
        $this->parts["content"] .= '<script>var text = '.json_encode(mb_convert_encoding($team->getContentAllArticles(), 'UTF-8', 'UTF-8')).';</script>';
        $this->parts["content"] .= '<script src="js/homeWordcloud.js"></script>';
        $this->parts["content"] .= "</div>";
        
        //NEWS ARTICLES
        foreach ($newsTitles as $token) {
            $this->parts["content"] .= "<h4><a href='".$this->router->getArticleURL(strtolower($teamName),array_search($token,$newsTitles))."'>".$token['title']." - ".$token['date']."</a></h4>";
        }

        $this->parts["content"] .= "</div>";
        $this->parts["content"] .= "<div id='nextGames'>";
        $this->parts["content"] .= "<h2 class='subtitles'>Next games of $teamName:</h2>";
        $this->parts["content"] .= "<h5 style='text-align: center;'><em>Click on</em><strong> [+] </strong><em>to see past games odds</em></h5>";

        $this->parts["content"] .= "
        <table id='mainTable'>
            <tr>
                <th></th>
                <th>Bookmaker</th>
                <th>1</th>
                <th>N</th>
                <th>2</th>
            </tr>
        ";


        foreach ($team->getNextGames() as $key => $value) {

            $teams = explode(" -", $value["details"]["name"]);
            if ($teamName === "Paris") {
                $editedTeamName = "Paris SG";
            }
            else {
                $editedTeamName = $teamName;
            }

            if(trim($teams[0]) === $editedTeamName) {
                $opponent = trim($teams[1]);
            }
            else {
                $opponent = trim($teams[0]);   
            }

            
            $this->parts["content"] .= "<tr class='bigTR'>";
            $this->parts["content"] .= "<td class='alignTop gameColumn' rowspan='".(count($value["odds"])+2)."'><p><span class='expand' id='$key'>[+]</span> <span class='matchName'>".$value["details"]["name"]."</span></p><p><em>".$value["details"]["date"]."</em></p>
                    <table class='historicTable' align='left'>
                        <tr>
                            <th></th>
                            <th>Date</th>
                            <th>1</th>
                            <th>N</th>
                            <th>2</th>
                        </tr>
                ";
            foreach (array_reverse($team->getOddsHistoric($opponent)) as $historic) {
                $this->parts["content"] .= "<tr class='oddTR'>
                    <td>".$historic[2]." - ".$historic[3]."</td>
                    <td>".$historic[1]."</td>
                    <td>".$historic[22]."</td>
                    <td>".$historic[23]."</td>
                    <td>".$historic[24]."</td>
                </tr>";
            }

            $this->parts["content"] .= "
                </table>
                </td>";
            $this->parts["content"] .= "</tr>";
            //var_dump($value);
            foreach ($value["odds"] as $bookmaker => $quota) {

                $this->parts["content"] .= "<tr class='quotas'>";
                $this->parts["content"] .= "<td class='alignTop'>$bookmaker</td>";
                $this->parts["content"] .= "<td class='alignTop'>".$quota["home"]."</td>";
                $this->parts["content"] .= "<td class='alignTop'>".$quota["draw"]."</td>";
                $this->parts["content"] .= "<td class='alignTop'>".$quota["away"]."</td>";
                $this->parts["content"] .= "</tr>";
                //break;
            }
            $this->parts["content"] .= "<tr class='stopQuotta'>";
            $this->parts["content"] .= "<td></td>";
            $this->parts["content"] .= "<td></td>";
            $this->parts["content"] .= "<td></td>";
            $this->parts["content"] .= "<td></td>";
            $this->parts["content"] .= "</tr>";
         } 
                

        $this->parts["content"] .= "</table>";
        $this->parts["content"] .= "<script src='js/betTables.js'></script>";

        
        $this->parts["content"] .= "</div>";
        $this->parts["content"] .= "</div>";
    }

    public function makeArticlePage($team,$newsArticle) {
        $this->parts["title"] = $newsArticle['title'];
        $this->parts["content"] = "<div id='article'>";
        $this->parts["content"] .= "<h4 style='text-decoration:underline;'>Content:</h4>";
        $this->parts["content"] .= "<p>".$newsArticle["content"]."</p>";

        $this->parts["content"] .= '<div id="bothWordcloud">';
        $this->parts["content"] .= '<div id="wordcloud">';
        $this->parts["content"] .= '<h3 class="wordcloudTitles">Article wordcloud</h3>';
        $this->parts["content"] .= '<script src="js/d3-wordcloud-master/lib/d3/d3.js"></script>';
        $this->parts["content"] .= '<script src="js/d3-wordcloud-master/lib/d3/d3.layout.cloud.js"></script>';
        $this->parts["content"] .= '<script src="js/d3-wordcloud-master/d3.wordcloud.js"></script>';
        $this->parts["content"] .= '<script>var text = '.json_encode($newsArticle["content"]).';</script>';
        $this->parts["content"] .= '<script src="js/wordcloud.js"></script>';
        $this->parts["content"] .= "</div>";

        $this->parts["content"] .= '<div id="teamWordcloud">';
        $this->parts["content"] .= '<h3 class="wordcloudTitles">Global wordcloud</h3>';

        $this->parts["content"] .= '<script>var text = '.json_encode(mb_convert_encoding($team->getContentAllArticles(), 'UTF-8', 'UTF-8')).';</script>';
        $this->parts["content"] .= '<script>
            var split = text.replace(/[.,;:?!<>«»]/g, "");
            var test = wordFreq(split);
            d3.wordcloud()
            .size([500, 400])
            .selector("#teamWordcloud")
            .words(test)
            .start();
            </script>';
        $this->parts["content"] .= "</div>";

        $this->parts["content"] .= "</div>";
        $this->parts["content"] .= "</div>";
    }

    public function makeUnknownImagePage() {
        $this->parts["title"] = "Erreur";
        $this->parts["content"] = "L'image demandée n'existe pas.";
    }

    public function makeUnexpectedErrorPage() {
        $this->parts["title"] = "Erreur";
        $this->parts["content"] = "Une erreur inattendue s'est produite.";
    }
}
