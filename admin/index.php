<?php
session_start();
require_once 'inc/header.php';
if (isset($_GET['page'])) {
    $_SESSION=null;
    $_SESSION['CID']='1';//for debug
    $_SESSION['ADMIN_URL']='http://'.$_SERVER['HTTP_HOST'].'/admin';

    $_SESSION['ADMIN_SETTINGS']=$_SESSION['ADMIN_URL'].'?page=settings';
    $_SESSION['ADMIN_POSITIONS']=$_SESSION['ADMIN_URL'].'?page=positions';
    $_SESSION['ADMIN_EVENTS']=$_SESSION['ADMIN_URL'].'?page=events';
    $_SESSION['ADMIN_INFO']=$_SESSION['ADMIN_URL'].'?page=info';
    $_SESSION['ADMIN_APPLICATIONS']=$_SESSION['ADMIN_URL'].'?page=applications';

    switch ($_GET['page']) {
        case 'settings': {
            $_SESSION['ann_manage']='active';
            require_once 'inc/announcements.php';
        }
            break;
        case 'positions': {
            $_SESSION['position_manage']='active';
        }
            break;
        case 'events':{
            $_SESSION['evt_manage']='active';
        }break;
        case 'info':{
            $_SESSION['info_manage']='active';
        }break;
        case 'applications': {
            $_SESSION['app_manage']='active';
        };
            break;
        default: {
            //default page
        }
    }
} else {
    //to login page
}
require_once 'inc/footer.php';