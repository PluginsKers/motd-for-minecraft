<?php
require_once('common.php');

if ($_REQUEST['query']) {
    header('Content-type: text/json');
    $array = [];
    $result = $DB->query("SELECT * FROM `list` ORDER BY `id` DESC");
    foreach (mysqli_fetch_all($result, MYSQLI_ASSOC) as $d) {
        $array[] = [$d['ip'], $d['port']];
    }
    exit(json_encode($array));
}
?>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="renderer" content="webkit">
    <meta name="viewport" content="width=device-width, initial-scale=1.0,maximum-scale=1.0, user-scalable=no">
    <meta http-equiv="Cache-Control" content="no-siteapp">
    <meta name="theme-color" content="#ff9800">
    <title>首页 - MOTD</title>
    <link rel="stylesheet" href="./assets/css/mdui.min.css">
    <link rel="stylesheet" href="./assets/css/style.css">

    <script type="text/javascript" src="./assets/js/jquery-3.4.1.min.js"></script>
</head>

<body class="mdui-theme-accent-orange mdui-text-color-orange mdui-theme-primary-orange">
    <div class="background"></div>
    <div class="mdui-appbar-with-toolbar">
        <div class="mdui-appbar mdui-appbar-fixed mdui-headroom">
            <div class="mdui-toolbar mdui-color-white mdui-color-theme">
                <button mdui-drawer="{target: '.mdui-drawer', overlay: true}" class="mdui-btn mdui-btn-icon">
                    <i class="mdui-icon material-icons">menu</i>
                </button>
                <a class="mdui-typo-headline" href="/">MOTD</a>
                <div class="mdui-typo-title mdui-hidden-xs-down">首页</div>
                <div class="mdui-toolbar-spacer"></div>
                <div class="mc-login-btn mdui-btn mdui-btn-dense mdui-ripple mdui-ripple-white">关于</div>
                <div class="mc-register-btn mdui-btn mdui-btn-dense mdui-ripple mdui-ripple-white">使用条款</div>
            </div>
        </div>
        <div class="mdui-drawer mdui-drawer-close mdui-color-white mdui-drawer-full-height">
            <div class="mdui-list">
                <a class="mdui-list-item mdui-ripple mdui-text-color-theme mdui-list-item-active" href="/">
                    <i class="mdui-list-item-icon mdui-icon material-icons">home</i>
                    <div class="mdui-list-item-content">首页</div>
                </a>
                <a href="/docs" class="mdui-list-item mdui-ripple"><i class="mdui-list-item-icon mdui-icon material-icons">book</i>
                    <div class="mdui-list-item-content">开发文档</div>
                </a>
            </div>
            <div class="copyright"></div>
        </div>

    </div>
    <div class="container">
        <div class="mdui-row">
            <div class="mdui-col-md-3 mdui-col-sm-12 sticky">
                <div class="status">
                    <div class="mdui-typo-title mdui-text-center"><strong>服务器状态查询</strong></div>
                    <div class="mdui-textfield mdui-textfield-floating-label">
                        <i class="mdui-icon material-icons">info_outline</i>
                        <label class="mdui-textfield-label" style="font-size: 14px;">IP地址</label>
                        <input class="mdui-textfield-input" name="ip">
                    </div>
                    <div class="mdui-textfield mdui-textfield-floating-label">
                        <i class="mdui-icon material-icons">security</i>
                        <label class="mdui-textfield-label" style="font-size: 14px;">端口</label>
                        <input class="mdui-textfield-input" name="port">
                    </div>
                    <button class="query mdui-btn mdui-btn-block mdui-color-theme-accent mdui-ripple" style="color: #fff!important;">查询</button>
                </div>
            </div>
            <div class="mdui-col-md-6 mdui-col-sm-8">
                <div class="list">
                    <div class="mdui-typo-title mdui-text-center"><strong>服务器列表</strong></div>
                    <ul class="mdui-list mdui-list-dense" id="server-list">

                    </ul>
                </div>
            </div>
            <div class="mdui-col-md-3 mdui-col-sm-4 sticky">
                <div class="header">
                    <div class="mdui-card">
                        <div class="mdui-card-media">
                            <img src="./assets/img/background.png" />
                        </div>
                        <div class="mdui-card-primary mdui-card-media-covered mdui-card-media-covered-top">
                            <div class="mdui-card-primary-title">MOTD平台</div>
                        </div>
                        <div class="mdui-card-content">
                            <small>
                                <p>平台于2020年2月29日上线，现我们无偿提供高速的API接口！</p>
                            </small>
                            <small>
                                <p>服务器节点于：香港<br>接口已被调用：589456次</p>
                            </small>
                            <p>Copyright © 2019-2020 MOTD Platform All Rights Reserved. MOTD平台 版权所有</p>
                        </div>
                        <div class="mdui-card-actions">
                            <button class="mdui-btn mdui-ripple mdui-btn-block">支持我们</button>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <div class="mdui-dialog" id="server">
        <div class="mdui-dialog-title" id="smotd">加载中</div>
        <div class="mdui-dialog-content" style="padding: 0;">
            <div class="mdui-table-fluid" style="overflow-x: hidden;box-shadow: none;border: none;">
                <table class="mdui-table mdui-table-hoverable">
                    <tbody>
                        <tr>
                            <td>IP</td>
                            <td id="sip">加载中</td>
                        </tr>
                        <tr>
                            <td>端口</td>
                            <td id="sport">加载中</td>
                        </tr>
                        <tr>
                            <td>服务器状态</td>
                            <td id="status">加载中</td>
                        </tr>
                        <tr>
                            <td>服务器版本</td>
                            <td id="sversion">加载中</td>
                        </tr>
                        <tr>
                            <td>协议版本</td>
                            <td id="sagreement">加载中</td>
                        </tr>
                        <tr>
                            <td>在线人数</td>
                            <td id="sonline">加载中</td>
                        </tr>
                        <tr>
                            <td>有效延迟</td>
                            <td id="sdelay">加载中</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="mdui-dialog-actions mdui-dialog-actions-stacked" style="padding: 0;">
            <button class="mdui-btn mdui-ripple" onclick="refreshInfo()">刷新服务器状态</button>
        </div>
    </div>

    <script type="text/javascript" src="./assets/js/mdui.min.js"></script>
    <script>
        var info = new mdui.Dialog("#server", {
            overlay: true
        });

        $(function() {
            $.post("./index.php", {
                "query": true
            }, function(a) {
                a = unique(a);
                for (e = 0; e < a.length; e++) {
                    $.get("./api.php?ip=" + a[e][0] + "&port=" + a[e][1], function(d) {
                        if (d['status'] == 'online') {
                            $("#server-list").append('<li class="mdui-list-item mdui-ripple" ip="' + d['ip'] + '" port="' + d['port'] + '" onclick="serverInfo(this)">' +
                                '<i class="mdui-icon material-icons mdui-text-color-green">check</i>' +
                                '<div class="mdui-list-item-content">' +
                                '<div class="mdui-list-item-title">' + d['motd'] + '</div>' +
                                '<div class="mdui-list-item-text mdui-list-item-one-line">在线<span id="lonline">' + d['online'] + '</span>人&nbsp;<span class="mdui-text-color-theme-text">' + d['ip'] + ':' + d['port'] + '</span><span class="mdui-text-color-theme-text mdui-float-right">延迟<span id="ldelay">' + d['delay'] + '</span>ms</span></div></div>' +
                                '</li>' +
                                '<li class="mdui-divider mdui-m-y-0"></li>');
                        }
                    });
                }
            });
        });

        $(".query").eq(0).click(function() {
            i = $("input[name='ip']").val();
            p = $("input[name='port']").val();
            refreshInfo(i, p);
        });

        function refreshInfo(ip = $("input[name='ip']").val(), port = $("input[name='port']").val()) {
            m = $("#smotd");
            i = $("#sip");
            p = $("#sport");
            s = $("#status");
            v = $("#sversion");
            a = $('#sagreement');
            o = $("#sonline");
            d = $("#sdelay");
            $.ajax({
                type: "post",
                url: "./api.php",
                data: {
                    "ip": ip,
                    "port": port
                },
                dataType: "json",
                cache: false,
                async: true,
                timeout: 1000,
                success: function(data) {
                    if (data['status'] == "online") {
                        m.text(data['motd']);
                        i.text(data['ip']);
                        p.text(data['port']);
                        s.text(data['status']);
                        v.text(data['version']);
                        a.text(data['agreement']);
                        o.text(data['online']);
                        d.text(data['delay'] + 'ms');
                        info.open();

                        if ($("li[ip='" + data['ip'] + "'][port='" + data['port'] + "']")) {
                            el = $("li[ip='" + data['ip'] + "'][port='" + data['port'] + "']");
                            el.find("#lonline").text(data['online']);
                            el.find("#ldelay").text(data['delay']);
                        }
                    } else {
                        mdui.snackbar({
                            timeout: 400,
                            position: 'right-bottom',
                            message: '服务器离线'
                        });
                    }
                },
                error: function() {
                    mdui.snackbar({
                        timeout: 400,
                        position: 'right-bottom',
                        message: '服务器离线'
                    });
                }
            });
        }

        function unique(arr) {
            var array = arr;
            var len = array.length;

            array.sort(function(a, b) {
                return a - b;
            })

            function loop(index) {
                if (index >= 1) {
                    if (array[index] === array[index - 1]) {
                        array.splice(index, 1);
                    }
                    loop(index - 1);
                }
            }
            loop(len - 1);
            return array;
        }

        function compare(p) {
            return function(m, n) {
                var a = m[p];
                var b = n[p];
                return a - b;
            }
        }

        function serverInfo(el) {
            i = el.getAttribute("ip");
            p = el.getAttribute("port");
            $("input[name='ip']").val(i);
            $("input[name='ip']").parent().addClass("mdui-textfield-focus");
            $("input[name='port']").val(p);
            $("input[name='port']").parent().addClass("mdui-textfield-focus");
            refreshInfo(i, p);
        };
    </script>
</body>

</html>