<?php
/**
 * Created by PhpStorm.
 * User: M
 * Date: 7/16/15
 * Time: 09:27
 */
require_once '../Class/club.php';// invoked by ajax request, using current path as its default path

if (isset($_GET['cid']) && isset($_GET['page'])) {
    $club_id = $_GET['cid'];
    $page = $_GET['page'];
    $club = new club($club_id);
    print($club->load_announcements(10, 10 * ($page - 1)));
} else {
    print('[]');
}