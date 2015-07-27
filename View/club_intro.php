<?php

/**
 * Created by PhpStorm.
 * User: M
 * Date: 7/15/15
 * Time: 12:01
 */
require_once 'Class/club.php';

class club_intro
{
    private $intro = null;

    function __construct($club_id)
    {
        $club = new club($club_id);
        $this->intro=$club->getIntro();
    }

    function export(){
        return $this->intro;
    }
}