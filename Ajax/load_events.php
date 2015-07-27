<?php
/**
 * Created by PhpStorm.
 * User: M
 * Date: 7/20/15
 * Time: 10:18
 */

require_once '../Class/club.php';

if (isset($_GET['cid']) && isset($_GET['page'])) {
    $club_id = $_GET['cid'];
    $page = $_GET['page'];
    $club = new club($club_id);
    print($club->load_events($club_id, 10, 10 * ($page - 1)));
} else {
    print('[]');
}