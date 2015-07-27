<?php
/**
 * Created by PhpStorm.
 * User: M
 * Date: 7/14/15
 * Time: 21:44
 */
require_once 'Class/application.php';

if (
    isset($_POST['portrait']) &&
    isset($_POST['cid']) &&
    isset($_POST['sid']) &&
    isset($_POST['name']) &&
    isset($_POST['telephone']) &&
    isset($_POST['email']) &&
    isset($_POST['qq']) &&
    isset($_POST['department']) &&
    isset($_POST['grade']) &&
    isset($_POST['subject']) &&
    isset($_POST['class']) &&
    isset($_POST['success']) &&
    isset($_POST['fail'])
) {
    $portrait = $_POST['portrait'];
    $cid = $_POST['cid'];
    $sid = $_POST['sid'];
    $name = $_POST['name'];
    $telephone = $_POST['telephone'];
    $email = $_POST['email'];
    $qq = $_POST['qq'];
    $department = $_POST['department'];
    $grade = $_POST['grade'];
    $subject = $_POST['subject'];
    $class = $_POST['class'];
    $success = base64_decode($_POST['success']);
    $fail = base64_decode($_POST['fail']);
    $array_answer = [];
    foreach ($_POST as $key => $val) {
        if (
            $key != 'portrait' &&
            $key != 'cid' &&
            $key != 'sid' &&
            $key != 'name' &&
            $key != 'telephone' &&
            $key != 'email' &&
            $key != 'qq' &&
            $key != 'department' &&
            $key != 'grade' &&
            $key != 'subject' &&
            $key != 'class' &&
            $key != 'success' &&
            $key != 'fail'
        ) {
            $item_set = ['id' => $key, 'answer' => $val];
            array_push($array_answer, $item_set);
        }
    }
    $app = new application($cid);
    if ($app->add_application($department, $sid, $name, $telephone, $email, $qq, $portrait, $grade, $subject, $class, json_encode($array_answer, JSON_UNESCAPED_UNICODE))) {
        header("Location: http://" . $success);
    } else {
        header("Location: http://" . $fail);
    }

} else {
    //redirect to error page
    echo 'ERROR';
}