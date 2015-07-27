<?php
/**
 * Created by PhpStorm.
 * User: M
 * Date: 7/9/15
 * Time: 15:17
 */
require_once './Class/university.php';
if (isset($_GET['unicode'])) {
    $uni = new university($_GET['unicode']);
    print($uni->getPortrait());
} else {
    print(null);
}