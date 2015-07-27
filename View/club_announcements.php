<?php

/**
 * Created by PhpStorm.
 * User: M
 * Date: 7/20/15
 * Time: 01:57
 */
class club_announcements
{
    private $content = '';
    private $cid = null;

    function __construct($club_id)
    {
        $this->cid = $club_id;
        $this->content .= $this->load_announcement_block();
        $this->content .= $this->load_more_button();
        $this->content .= $this->load_script();
    }

    private function load_announcement_block()
    {
        return '
        <section class="announcements" id="announcements">
        </section>
        ';
    }

    private function load_more_button()
    {
        return '<button class="button" id="load_more" onclick="announcement()">加载更多公告</button>';
    }

    private function load_script()
    {
        return <<<EOF
<script>
    var current_page = 1;
    announcement();
    function announcement() {
        var url = window.location.host;
        ajax('http://' + url + '/Ajax/load_announcements.php?cid=$this->cid&page=' + current_page, function (data) {
            var json_data = eval('(' + data + ')');//convert to json objects
            if (json_data.length < 10) {
                //end data
                document.getElementById('load_more').style.visibility='hidden';
            }
            var selection = document.getElementById('announcements');
            for (var i = 0; i < json_data.length; i++) {
                var id = json_data[i]['id'];
                var text = json_data[i]['text'];
                var datetime = json_data[i]['datetime'];
                var announcement = "<article class='announcement'><span class='announcement-time'><p>" + datetime + "</p></span><div class='announcement-body'><div class='text'><p>" + text + "</p></div></div></article>";
                selection.innerHTML+=announcement;
            }
        });
        current_page++;
    }

    function ajax(requestTarget, callback) {
        var xmlhttp = null;
        if (window.XMLHttpRequest) {
            xmlhttp = new XMLHttpRequest();
            xmlhttp.open('GET', requestTarget, true);
            xmlhttp.send();
            xmlhttp.onreadystatechange = function () {
                if (xmlhttp.readyState === 4 && xmlhttp.status === 200) {
                    callback(xmlhttp.responseText);
                }
            }
        } else {
            alert('您的浏览器不支持XMLHttprequest，请您更换浏览器后再使用本程序。');
        }
    }
</script>
EOF;

    }

    function export()
    {
        return $this->content;
    }
}