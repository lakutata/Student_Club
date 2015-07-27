<?php

/**
 * Created by PhpStorm.
 * User: M
 * Date: 7/20/15
 * Time: 09:55
 */
class club_events
{
    private $content = '';
    private $cid = null;

    function __construct($club_id)
    {
        $this->cid = $club_id;
        $this->content .= $this->load_structure();
        $this->content.=$this->load_button();
        $this->content.=$this->load_script();
    }

    function load_script()
    {
        return <<<EOF
<script>
    var current_page = 1;
    events();
    function events() {
        var url = window.location.host;
        ajax('http://' + url + '/Ajax/load_events.php?cid=$this->cid&page=' + current_page, function (data) {
            var json_data = eval('(' + data + ')');//convert to json objects
            if (json_data.length < 10) {
                //end data
                document.getElementById('load_more').style.visibility='hidden';
            }
            var selection = document.getElementById('events');
            for (var i = 0; i < json_data.length; i++) {
                var id = json_data[i]['id'];
                var event = json_data[i]['event'];
                var date = json_data[i]['date'];
                var struc="<li id='"+id+"'><b></b><span>"+date+"</span><p>"+event+"</p></li>";
                selection.innerHTML+=struc;
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

    function load_structure()
    {
        return '
        <div class="times">
        <ul id="events">
        </ul>
        </div>
        ';
    }

    function load_button(){
        return '
        <button id="load_more" class="button" onclick="events()">加载更多社团纪事</button>
        ';
    }

    function export()
    {
        return $this->content;
    }
}