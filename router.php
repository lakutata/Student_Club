<?php

/**
 * Created by PhpStorm.
 * User: M
 * Date: 7/10/15
 * Time: 23:13
 */
require_once 'Class/mysql.php';
require_once 'Class/university.php';
require_once 'Class/club.php';

class router
{
    private $db = null;

    function __construct()
    {
        $_SESSION['CURRENT_URL'] = $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
        $this->db = new mysql();
        if (isset($_GET['university'])) {
            $uni = $_GET['university'];
            $_SESSION['university_id'] = $this->db->exec_select('university', ['id'], ['name' => $uni], 1)[0]['id'];
            if (isset($_GET['club'])) {
                //to club page
                $cname = $_GET['club'];
                $_SESSION['club_id'] = $this->db->exec_select('club', ['id'], ['name' => $cname], 1)[0]['id'];
                if ($_SESSION['club_id']) {
                    $club = new club($_SESSION['club_id']);
                    require_once 'View/club_index.php';
                    $club_index = new club_index($_SESSION['club_id'], $club->getName(), $club->getPortrait());
                    $_SESSION['CLUB_INDEX'] = $_SERVER['HTTP_HOST'] . '?university=' . $uni . '&club=' . $cname;
                    $_SESSION['CLUB_ANNOUNCEMENTS'] = $_SERVER['HTTP_HOST'] . '?university=' . $uni . '&club=' . $cname . '&selection=announcements';
                    $_SESSION['CLUB_EVENTS'] = $_SERVER['HTTP_HOST'] . '?university=' . $uni . '&club=' . $cname . '&selection=events';
                    $_SESSION['CLUB_APPLICATIONS'] = $_SERVER['HTTP_HOST'] . '?university=' . $uni . '&club=' . $cname . '&selection=application';
                } else {
                    //the page of not existing club
                }
            } else {
                //to university club selection page
            }
        } else {
            //to university selection page
        }
    }
}