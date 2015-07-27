<?php
/**
 * Created by PhpStorm.
 * User: M
 * Date: 7/9/15
 * Time: 15:17
 */
require_once './Class/club.php';
if (isset($_GET['cid'])) {
    $club = new club($_GET['cid']);
    print($club->getIntro());
} else {
    print(null);
}