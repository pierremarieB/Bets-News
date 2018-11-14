<?php
namespace BetCompare\Application\Teams;

use BetCompare\Application\Teams\Team;
use BetCompare\Application\Teams\TeamStorage;

class TeamStorageStub implements TeamStorage
{
    protected $db;

    public function __construct()
    {
        $this->db = array();

        $dirname = "images/teams/";
        $images = glob($dirname."*.{png,PNG}", GLOB_BRACE);

        foreach ($images as $image) {
            $teamName = explode(".",$image)[0];
            if($teamName === "paris") {
                $this->db[] = new Team("Paris",["PSG","Paris Saint-Germain F.C.","Paris Saint-Germain","Paris"]);
            }
            else {
                $this->db[] = new Team(ucfirst($teamName),array(ucfirst($teamName)));
            }
        }
    }

    public function read($id)
    {
        if (key_exists($id, $this->db)) {
            return $this->db[$id];
        }
        return null;
    }

    public function readName($name)
    {   
        foreach ($this->db as $team) {
            //$var_dump($team->getName());
            if ($team->getName() === $name) {
                return $team;
            }
        }
        return null;
    }

    public function readAll()
    {
        return $this->db;
    }
}
