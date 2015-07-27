<?php
/**
 * Created by PhpStorm.
 * User: M
 * Date: 7/25/15
 * Time: 12:59
 */
require_once '../Class/club.php';
if (isset($_GET['announcement']) && isset($_GET['cid'])) {
    $club = new club($_GET['cid']);
    if ($club->add_announcements($_GET['announcement'])) {
        print('[{"result":"true"}]');
    } else {
        print('[{"result":"false"}]');
    }
} else {
    print('[{"result":"false"}]');
}