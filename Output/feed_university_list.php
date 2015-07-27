<?php
/**
 * Created by PhpStorm.
 * User: M
 * Date: 7/9/15
 * Time: 14:20
 */
require_once './Class/university.php';
$uni = new university();
print($uni->get_all_universities());