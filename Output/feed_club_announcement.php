<?php
/**
 * Created by PhpStorm.
 * User: M
 * Date: 7/9/15
 * Time: 16:22
 */
require_once './Class/club.php';

$limit=10;

if(isset($_GET['cid'])&&isset($_GET['page'])){
    $club_id=$_GET['cid'];
    $club=new club($club_id);
    $page=$_GET['page'];
    if($page>1){
        print($club->load_announcements($limit,$limit*($page-1)));
    }else{
        print($club->load_announcements($limit,0));
    }
}else{
    print(null);
}