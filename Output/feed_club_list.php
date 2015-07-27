<?php
/**
 * Created by PhpStorm.
 * User: M
 * Date: 7/9/15
 * Time: 14:51
 */
require_once './Class/university.php';
if(isset($_GET['unicode'])){
    $university_id = $_GET['unicode'];
    $uni=new university($university_id);
    print($uni->get_clubs());
}else{
    print(null);
}
