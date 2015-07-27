<div id="announcements">
    <div class="panel panel-default">
        <!-- Default panel contents -->
        <div class="panel-heading">公告管理</div>
        <div class="panel-body">
            <p>
                <textarea id="announcement_text" class="form-control" rows="5"></textarea>
                <button id="send_btn" class="btn btn-default" onclick="send_announcement()">发布公告</button>
            </p>
        </div>

        <!-- Table -->
        <table class="table" id="ann_list">
            <tr>
                <th>发布日期</th>
                <th>操作</th>
                <th>公告内容</th>
            </tr>
            <!--                        <tr>-->
            <!--                            <td>2015-09-11 23:04:03</td>-->
            <!--                            <td><a href="#">删除</a></td>-->
            <!--                            <td>dsfsadfsdfsadf试试看的减肥哈斯卡-->
            <!--                                SD卡发货速度拉风回来看黄色撒发货开始了巨大回复撒旦覅uh算地方和啦uehfiuwheiluf哈维礼服阿费莱撒uehfusahf收到回复了use恒丰路撒光华路iadgdshgi韩国iuhsdgiauhadlsguhdslufh回复uhdsufiahslfuehlisuehiuehsiulf和六分饿死use和覅ue-->
            <!--                            </td>-->
            <!--                        </tr>-->
        </table>
        <nav>
            <ul class="pager">
                <li><a href="#">上一页</a></li>
                <li><a href="#">下一页</a></li>
            </ul>
        </nav>
    </div>

</div>

<script>
    var send_btn = document.getElementById('send_btn');
    var announcement_text = document.getElementById('announcement_text');
    var announcement_list = document.getElementById('ann_list');
    var current_ann_list_page = 1;
    load_announcement(current_ann_list_page);
    function send_announcement() {
        var host = "<?php echo('http://'.$_SERVER['HTTP_HOST'].'/Ajax/add_announcement.php?cid='.$_SESSION['CID']); ?>";
        if (announcement_text.value != "" && announcement_text.value.length < 200) {
            host += '&announcement=' + announcement_text.value;
            Ajax(host, function (result) {
                var json_result = eval(result);
                if (json_result[0].result) {
                    announcement_text.value = "";
                    alert('公告发布成功');
                } else {
                    alert('公告发布失败，请稍后再试');
                }
            });
        }
    }

    function load_announcement(page) {
        var host = "<?php echo('http://'.$_SERVER['HTTP_HOST'].'/Ajax/load_announcements.php?cid='.$_SESSION['CID']); ?>" + "&page=" + page;
        Ajax(host, function (result) {
            announcement_list.innerHTML = "<tr> <th>发布日期</th><th>操作</th><th>公告内容</th ></tr>";
            json_result = eval(result);
            for (var i = 0; i < json_result.length; i++) {
                announcement_list.innerHTML += "<tr> <td>" + json_result[i]['datetime'] + "</td><td><a href = '' >删除</a></td><td>"+json_result[i]['text']+"</td> </tr>";
            }
        });
    }
</script>

<style>
    #announcements {
        text-align: center;
    }

    #announcements .panel {
        width: 80%;
        max-width: 80%;
        min-width: 80%;
        display: inline-block;
    }

    #announcements .panel table {
        text-align: left;
    }

    #announcements .form-control {
        width: 80%;
        max-width: 80%;
        min-width: 80%;
        display: inline-block;
    }

    #announcements button {
        display: block;
        margin: 5px auto;
    }

    #announcements table {
        width: 80%;
        max-width: 80%;
        min-width: 80%;
        display: inline-block;
    }
</style>