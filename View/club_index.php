<?php
require_once 'application_form.php';
require_once 'club_intro.php';
require_once 'club_announcements.php';
require_once 'club_events.php';

class club_index
{
    function __construct($id, $name, $portrait)
    {
        $_SESSION['club_id'] = $id;
        $this->output($name, $portrait);
    }

    private function output($name, $portrait)
    {
        $content = null;
        $selection = '';
        if (isset($_GET['selection'])) {
            $selection = $_GET['selection'];
        }
        switch ($selection) {
            case 'announcements': {
                $this->announcements = 'active';
                $announcements = new club_announcements($_SESSION['club_id']);
                $content = $announcements->export();
            }
                break;
            case 'events': {
                $this->events = 'active';
                $events = new club_events($_SESSION['club_id']);
                $content = $events->export();
            }
                break;
            case 'application': {
                $this->application = 'active';
                $form = new application_form($_SESSION['club_id']);
                $content = $form->export();
            }
                break;
            default: {
                $this->intro = 'active';
                $intro = new club_intro($_SESSION['club_id']);
                $content = $intro->export();
            }
        }
        print(
            '<!DOCTYPE HTML>
<html>
<head>
    <title></title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <!--[if lte IE 8]>
    <script src="../assets/js/ie/html5shiv.js"></script><![endif]-->
    <link rel="stylesheet" href="../assets/css/main.css" />
    <link rel="stylesheet" href="../assets/css/timeline.css" />
    <link rel="stylesheet" href="../assets/css/events_timeline.css" />
    <!--[if lte IE 8]>
    <link rel="stylesheet" href="../assets/css/ie8.css"/><![endif]-->
</head>
<body>

<!-- Header -->
<section id="header">
    <header>
        <span class="image avatar"><img src="' . $portrait . '" alt="" /></span> <!-- 头像 -->
        <h1 id="logo">' . $name . '</h1> <!-- 协会名称 -->
    </header>
    <nav id="nav">
        <ul>
            <li><a href="http://' . $_SESSION['CLUB_INDEX'] . '" class="' . $this->intro . '">社团简介</a></li>
            <li><a href="http://' . $_SESSION['CLUB_ANNOUNCEMENTS'] . '" class="' . $this->announcements . '">社团公告</a></li>
            <li><a href="http://' . $_SESSION['CLUB_EVENTS'] . '" class="' . $this->events . '">社团纪事</a></li>
            <li><a href="http://' . $_SESSION['CLUB_APPLICATIONS'] . '" class="' . $this->application . '">申请加入</a></li>
        </ul>
    </nav>
    <footer>
					<span class="copyright">
						<a href="#">通讯录</a>&nbsp;|&nbsp;
						<a href="#">公告发布</a>&nbsp;|&nbsp;
						<a href="#">入会审核</a>&nbsp;|&nbsp;
						<a href="#">社团设置</a>
						<br>
						Lakutata.com &copy; 2013-2015. All rights reserved
					</span>
    </footer>
</section>

<!-- Wrapper -->
<div id="wrapper">

    <!-- Main -->
    <div id="main">

        <!-- One -->
        <section id="one">
            <div class="container">
<!--                内容区域-->
' . $content . '
            </div>
        </section>



    </div>

    <!-- Footer
        <section id="footer">
            <div class="container">
                <ul class="copyright">

                </ul>
            </div>
        </section>

</div>-->

    <!-- Scripts -->
    <script src="../assets/js/jquery.min.js"></script>
    <script src="../assets/js/jquery.scrollzer.min.js"></script>
    <script src="../assets/js/jquery.scrolly.min.js"></script>
    <script src="../assets/js/skel.min.js"></script>
    <script src="../assets/js/util.js"></script>
    <!--[if lte IE 8]>
    <script src="../assets/js/ie/respond.min.js"></script><![endif]-->
    <script src=".//assets/js/main.js"></script>

</body>
</html>'
        );
    }
}