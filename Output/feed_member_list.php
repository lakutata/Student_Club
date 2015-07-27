<?php
/**
 * Created by PhpStorm.
 * User: M
 * Date: 7/10/15
 * Time: 12:35
 */
require_once './Class/member.php';
require_once './Class/club.php';

if (isset($_GET['cid']) && isset($_GET['page']) && isset($_GET['type']) && isset($_GET['keyword'])) {
    $member = new member($_GET['cid']);
    $keyword = $_GET['keyword'];
    $page=$_GET['page'];
    $limit=20;
    $offset=$limit*($page-1);
    switch (strtoupper($_GET['type'])) {
        case 'DEPARTMENT': {
            if (isset($_GET['grade'])) {
                print($member->get_members_by_department_id($_GET['grade'], $keyword,$limit,$offset));
            } else {
                print(json_encode([]));
            }
        }
            break;
        case 'NAME': {
            print($member->get_members_by_name($keyword,$limit,$offset));
        }
            break;
        case 'TELEPHONE': {
            print($member->get_members_by_telephone($keyword,$limit,$offset));
        }
            break;
        case 'EMAIL': {
            print($member->get_members_by_email($keyword,$limit,$offset));
        }
            break;
        case 'QQ': {
            print($member->get_members_by_qq($keyword,$limit,$offset));
        }
            break;
        case 'GRADE': {
            print($member->get_members_by_grade($keyword,$limit,$offset));
        }
            break;
        case 'SID': {
            print($member->get_members_by_student_id($keyword,$limit,$offset));
        }
            break;
        default:
            print(json_encode([]));
    }
}