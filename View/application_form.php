<?php

/**
 * Created by PhpStorm.
 * User: M
 * Date: 7/13/15
 * Time: 21:46
 */
require_once 'Class/application.php';

class application_form
{
    private $content = '';

    function __construct($club_id)
    {
        $app = new application($club_id);
        $questions = $app->get_questions_internal();
        //read all questions of this club
        $this->content .= $this->load_portrait_box();
        $this->content .= '<form method="post" action="handle_application.php" name="Application form" id="application_form" onsubmit="check();">' . "\n";
        $this->content .= $this->basic_question($club_id);
        foreach ($questions as $question) {
            $this->content .= $this->optional_question_gen($question['question'], $question['id']);
        }
        $this->content .= '<input id="submit" type="submit" value="submit" class="button" style="display:none">' . "\n";//load submit button
        $this->content .= '</form>' . "\n";
        $this->content.='<button class="button" id="pseudo_submit">提交申请</button>'."\n";
        $this->content .= $this->load_end_content();
    }

    private function optional_question_gen($question, $id)
    {
        return ('
        <span><label>' . $question . '</label>
        <input type="text" name="' . $id . '"></span>
        ' . "\n");
    }

    private function basic_question($cid)
    {
        $departments = new club($cid);
        $departments_info = $departments->get_departments_open();
        $departmes_str = '';
        for ($i = 0; $i < count($departments_info); $i++) {
            if ($i == 0) {
                $departmes_str .= '<select name="department" value="' . $departments_info[$i]['id'] . '">' . "\n";
            }
            $departmes_str .= '<option value="' . $departments_info[$i]['id'] . '">' . $departments_info[$i]['name'] . '</option>' . "\n";
            if ($i == count($departments_info) - 1) {
                $departmes_str .= '</select></span>' . "\n";
            }
        }
        $default_grade = date('Y');
        return ('
        <input type="text" name="portrait" id="portrait_data" style="display:none" >
        <input type="text" name="cid" id="cid" value="' . $cid . '" style="display:none" >
        <input type="text" name="success" value="' . base64_encode($_SESSION['CLUB_INDEX']) . '" style="display:none" >
        <input type="text" name="fail" value="' . base64_encode($_SESSION['CLUB_APPLICATIONS']) . '" style="display:none" >
        <span><label>学号</label><input type="text" name="sid" id="sid"></span>
        <span><label>姓名</label><input type="text" name="name" id="name"></span>
        <span><label>手机</label><input type="text" name="telephone" id="telephone"></span>
        <span><label>Email</label><input type="text" name="email" id="email"></span>
        <span><label>QQ</label><input type="text" name="qq" id="qq"></span>
        <span><label>申请部门</label>
        ' . $departmes_str . '
       <span><label>年级</label>
        <select name="grade" value="' . $default_grade . '">
        <option value="' . $default_grade . '">' . $default_grade . '</option>
        <option value="' . ($default_grade - 1) . '">' . ($default_grade - 1) . '</option>
        <option value="' . ($default_grade - 2) . '">' . ($default_grade - 2) . '</option>
        <option value="' . ($default_grade - 3) . '">' . ($default_grade - 3) . '</option>
        </select></span>
        <span><label>专业</label><input type="text" name="subject" id="subject"></span>
        <span><label>班级</label><input type="text" name="class" id="class"></span>
        ' . "\n");
    }

    private function load_portrait_box()
    {
        return <<<EOF
<div id="portrait_area">
    <canvas id="portrait">您的浏览器不支持此功能，请更换浏览器后再试</canvas>
    <button type="button" class="button" onClick="document.getElementById('portrait_file').click()">上传头像</button>
    <input type="file" single accept="image/jpeg,image/png,image/bmp" id="portrait_file" style="display:none" onchange="loadImage()">
</div>
EOF;

    }

    private function load_end_content()
    {
        return <<<EOF
        <style>
    #portrait {
        box-shadow: 0 0 1px 1px #333333;
        width: 240px;
        height: 320px;
        display:block;
        margin: 5px auto;
    }

    #portrait_area{
        display: block;
        margin: 0 auto;
    }
    #portrait_area button{
        display: block;
        margin: 5px auto;
    }

    #application_form{
        width: 70%;
        display: inline-block;
        position: relative;
    }

    #application_form span{
        display: block;
        text-align: left;
        width: 100%;
        position: relative;
        margin: 10px auto;
        padding-top: 10px;
        box-shadow: 0 0 1px 2px #DDDDDD;
        padding: 10px;
    }

    #application_form span label{
        min-width: 100px;
        width: auto;
        display: inline-block;
    }

    #application_form span input{
        width: 100%;
        display: inline-block;
    }

    #application_form span select{
        width: 50%;
        display: inline-block;
        text-align: center;
    }

    #submit{
        display:block;
        margin:0 auto;
    }

    #pseudo_submit{
        display:block;
        margin:0 auto;
        width:150px;
        font-size:15px;
    }

    form{
        margin-bottom:5px;
    }

    body{
        text-align: center;
    }
</style>
<script>
    var canvas=document.getElementById('portrait');
    var canvas_ctx=canvas.getContext('2d');
    var portrait_data=document.getElementById('portrait_data');
    portrait_data.value=null;
    //init canvas
    canvas.width=240;
    canvas.height=320;
    function loadImage(){
        canvas_ctx.clearRect(0,0,canvas.offsetWidth,canvas.offsetHeight);
        var portrait_file=document.getElementById('portrait_file').files[0];
        if(window.FileReader){
            var fr = new FileReader();
            fr.onload=function(e){
                var img=new Image();
                img.src=e.target.result;
                img.onload=function(){
                    canvas_ctx.drawImage(img,0,0,canvas.offsetWidth,canvas.offsetHeight);
                    var new_img_data=canvas.toDataURL('image/jpeg');
                    portrait_data.value=new_img_data;
                }
            }
            fr.onerror=function(){
                alert("头像加载的过程中发生了系统错误，请刷新页面重试");
            }
            fr.readAsDataURL(portrait_file);
        }else{
            alert("您的浏览器不支持文件上传功能，请更换浏览器后再试.");
        }
    }

    function check(){
    if(
        document.getElementById('portrait_data').value!=""&&
        document.getElementById('cid').value!=""&&
        document.getElementById('sid').value!=""&&
        document.getElementById('name').value!=""&&
        document.getElementById('telephone').value!=""&&
        document.getElementById('email').value!=""&&
        document.getElementById('qq').value!=""&&
        document.getElementById('subject').value!=""&&
        document.getElementById('class').value!=""
        ){
        return true;
        }else{
        return false;
        }
    }

    setInterval(function(){
        if(check()){
            document.getElementById('pseudo_submit').onclick=function(){
                document.getElementById('submit').click();
            }
        }else{
            document.getElementById('pseudo_submit').onclick=function(){
                alert('请填写完基本信息后再提交！');
            }
        }
    },100);
</script>
EOF;

    }

    function export()
    {
        return $this->content;
    }
}