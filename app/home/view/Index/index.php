<!DOCTYPE HTML>
<html class="fullcreen" debug="true">
<head>
    <meta http-equiv="content-type" content="text/html;charset=utf-8"/>
    <meta name="viewport" content="width=device-width,user-scalable=no"/>
    <meta name="apple-mobile-web-app-capable" content="yes" />
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent" />
    <meta content="telephone=no,email=no" name=”format-detection” />
    <title>录音测试</title>
    <script src="http://res.wx.qq.com/open/js/jweixin-1.0.0.js"></script>
    <link href="http://cdn.amazeui.org/amazeui/2.4.2/css/amazeui.min.css" rel="stylesheet">
    <script type="text/javascript" src="http://pic.weibopie.com/wxapp/gz/commonJs/jquery.min.js"></script>
    <script type="text/javascript" src="http://cdn.amazeui.org/amazeui/2.4.2/js/amazeui.min.js"></script>
</head>
<script>
    wx.config({
        debug: true,
        appId: '<?php echo $js['appId'];?>',
        timestamp: <?php echo $js['timestamp'];?>,
        nonceStr: '<?php echo $js['nonceStr'];?>',
        signature: '<?php echo $js['signature'];?>',
        jsApiList: [
            'checkJsApi',
            'onMenuShareTimeline',
            'onMenuShareAppMessage',
            'onMenuShareQQ',
            'onMenuShareWeibo',
            'onMenuShareQZone',
            'hideMenuItems',
            'showMenuItems',
            'hideAllNonBaseMenuItem',
            'showAllNonBaseMenuItem',
            'translateVoice',
            'startRecord',
            'stopRecord',
            'onVoiceRecordEnd',
            'playVoice',
            'onVoicePlayEnd',
            'pauseVoice',
            'stopVoice',
            'uploadVoice',
            'downloadVoice',
            'chooseImage',
            'previewImage',
            'uploadImage',
            'downloadImage',
            'getNetworkType',
            'openLocation',
            'getLocation',
            'hideOptionMenu',
            'showOptionMenu',
            'closeWindow',
            'scanQRCode',
            'chooseWXPay',
            'openProductSpecificView',
            'addCard',
            'chooseCard',
            'openCard'
        ]
    });
</script>
<body>
<div class="am-g" style="text-align: center">
    <div class="am-u-lg-6 am-u-md-8 am-u-sm-centered">
        <h3>录音测试</h3>
        <hr>
        <div class="am-cf">
            <input type="button" onclick="start()" value="开始录音" class="am-btn am-btn-primary am-btn-sm am-fl">
            <input type="button" style="float:right;" onclick="stop()" value="停止录音" class="am-btn am-btn-primary am-btn-sm am-fl">
        </div>
        <hr>
        <p>@随视传媒测试 © 2015 Charon </p>
    </div>
</div>
<script>
    var url = '';
    var voice = {
        localId: '',
        serverId: ''
    };

    /*wx.ready(function () {
        wx.startRecord({
            success:function(){
                wx.stopRecord({
                    success : function(res){
                        voice = {localId: '', serverId: ''};
                    }
                });
            },
            cancel: function () {
                alert('您拒绝了授权录音');
            }
        });
    });*/

    var start = function()
    {
        wx.ready(function () {
            wx.startRecord({
                cancel: function () {
                    wx.stopRecord({
                        success : function(res){
                            voice = {localId: '', serverId: ''};
                        }
                    });
                    alert('您拒绝了授权录音');
                }
            });
        });
    };

    var stop = function(){
        wx.ready(function () {
            wx.stopRecord({
                success: function (res) {
                    voice.localId = res.localId;
                    download(voice.localId);
                },
                fail: function (res) {
                    alert(JSON.stringify(res));
                }
            });
        });
    };

    var download = function(localId) {
        wx.ready(function () {
            if (localId  == '') {
                alert('请先使用 startRecord 接口录制一段声音');
                return;
            }
            wx.uploadVoice({
                localId: localId,
                success: function (res) {
                    voice.serverId = res.serverId;
                    $.post(url , {mid:voice.serverId} , function(response){
                        response = JSON.parse(response);
                        alert(response.msg);
                    });
                }
            });
        });
    };

    wx.ready(function () {
        wx.onVoiceRecordEnd({
            complete: function (res) {
                download(res.localId);
                alert('录音时间已超过一分钟');
            }
        });
    });
</script>
</body>
</html>