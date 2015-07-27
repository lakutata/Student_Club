<?php
/**
 * Created by PhpStorm.
 * User: M
 * Date: 7/25/15
 * Time: 13:20
 */

require_once '../Class/club.php';
if (isset($_GET['cid']) && isset($_GET['aid'])) {
    $club = new club($_GET['cid']);
    if ($club->delete_announcement($_GET['aid'])) {
        print('[{"result":"true"}]');
    } else {
        print('[{"result":"false"}]');
    }
} else {
    print('[{"result":"false"}]');
}