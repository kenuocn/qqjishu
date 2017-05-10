<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title><?php echo ($SEO['site_title']); ?></title>
	<meta name="keywords" content="<?php echo ($SEO['keyword']); ?>">
	<meta name="description" content="<?php echo ($SEO['description']); ?>">
	<link rel="stylesheet" href="<?php echo ($ANSEL_URL); ?>css/qqzs.css">
	<link rel="stylesheet" href="<?php echo ($ANSEL_URL); ?>css/font-awesome.css?v=1">
	<script src="<?php echo ($ANSEL_URL); ?>js/banner.js?v=1"></script>
</head>

<body>
<div class="head">
    <div class="box">
        <ul class="nav-list">
            <li class="sign right">
                <a class="user-account ">用户中心</a>
                <div class="drop-menu ">
                    <a class="drop-menu-item" href="<?php echo U('index/user/account');?>">我的账户</a>
                    <a class="drop-menu-item" href="admin/orders">我的订单</a>
                    <a class="drop-menu-item" href="logout.php">退出登录</a>
                </div>
            </li>
            <li class="logo"></li>
            <li class="item"><a href="index.html">首页</a></li>
        </ul>
    </div>
</div>
<div class="body">
    <div class="box">
        <div class="banner-box">
            <div class="left banner">
                <div class="wrap" id="wrap">
                    <img src="http://admin.qqzhus.com/public/images/u=1027448524,3307017028&fm=23&gp=0.jpg" alt="">
                    <img src="http://admin.qqzhus.com/public/images/u=1027448524,3307017028&fm=23&gp=0.jpg" alt="">
                </div>
                <div class="prev" id="prev"><i class="fa icon-chevron-left icon-2x"></i></div>
                <div class="next" id="next"><i class="fa icon-chevron-right icon-2x"></i></div>
            </div>
            <div class="right news">
                <h2 class="news-ti ti-h2">最新资讯</h2>
                <ul class="news-list">
                    <li><a href="news.html?id=1">【免费QQ助手】免费QQ助手简介，下单前必读！！</a></li>
                    <li><a href="news.html?id=4">【免费QQ助手】下单注意事项！！每天凌晨开刷，不要重复提交一个QQ</a></li>
                    <li><a href="news.html?id=5">【免费QQ助手】本站承诺：本站所有业务永久免费，不会收取任何费用，请收藏好！！</a></li>
                    <li><a href="news.html?id=3">【免费QQ助手】怎样快速的获取积分！！</a></li>
                    <li><a href="news.html?id=2">【免费QQ助手】本站使用方法！！</a></li>
                </ul>
            </div>
        </div>
    </div>
    <div class="query-container">
        <div class="box">
            <div class="query-box">
                <h2 class="query-ti ti-h2">
          查询功能
        </h2>
                <div class="query-form">
                    <input type="text" placeholder="请输入QQ号" class="input" id="qq_num">
                    <button id="ren_q">查询人气</button>
                    <button id="liu_q">查询留言</button>
                    <button id="shuo_q">获取说说ID</button>
                    <div class="query-result left" id="result_q">
                        查询结果
                    </div>
                    <select name="shuos" id="shuos" class="select hide"></select>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="deal-container">
    <div class="box">
        <h2 class="deal-ti ti-h2">
        业务列表
      </h2>
        <ul class="deal-list">
            <li>
                <h2 class="title">QQ空间人气</h2>
                <p class="tip">QQ空间人气，每次下单送100人气，每个号码每天可以提交一次。下单前请检查空间是否允许任何人访问，是否有原创说说，请勿恶意提交</p>
                <a class="btn">立即下单</a>
                <div class="form">
                    <div class="item">
                        <span class="lable">QQ</span>
                        <input type="text" placeholder="请输入QQ号" name="qqNum">
                    </div>
                    <a class="btn-submit">提交订单</a><a class="btn-back">收起</a>
                    <input type="hidden" value="11" name="id">
                    <input type="hidden" value="0" name="point">
                    <input type="hidden" value="3" name="type">
                </div>
            </li>
            <li>
                <h2 class="title">手机QQ名片赞</h2>
                <p class="tip">手机QQ名片赞，100积分兑换100赞。 积分获取方法：1.签到送积分 2.邀请送积分</p>
                <a class="btn">立即下单</a>
                <div class="form">
                    <div class="item">
                        <span class="lable">QQ</span>
                        <input type="text" placeholder="请输入QQ号" name="qqNum">
                    </div>
                    <a class="btn-submit">提交订单</a><a class="btn-back">收起</a>
                    <input type="hidden" value="2" name="id">
                    <input type="hidden" value="100" name="point">
                    <input type="hidden" value="3" name="type">
                </div>
            </li>
        </ul>
    </div>
</div>
</div>
<div class="foot">
    <?php echo ($config['copyright']); ?>&nbsp;<?php echo ($config['icp']); ?> | <a target="_blank" href="http://www.umeng.com/">站长统计</a>
</div>
<div style="display:none">
</div>
<!-- <div class="dialog dialog-tip" id="dialog_tip">注册成功注册成功注</div> -->
<script src="<?php echo ($ANSEL_URL); ?>js/jquery.min.js"></script>
<div class="mask"></div>
<div class="dialog dialog-sign-in hide">
    <h2 class="sign-in-ti ti-h2">
		<i class="right fa fa-times-rectangle icon-close" id="sign_in_close"></i>
		帐号登录
	</h2>
    <div class="item">
        <span class="label">用户名</span>
        <input type="text" placeholder="请输入用户名" id="sign_in_name">
        <span class="error" id="error_in_name"></span>
    </div>
    <div class="item">
        <span class="label">密码</span>
        <input type="password" placeholder="请输入密码" id="sign_in_pwd">
        <span class="error" id="error_in_pwd"></span>
    </div>
    <div class="item forget-pwd">
        <a class="forget-pwd-btn">忘记密码?</a>
    </div>
    <div class="item">
        <a class="sign-in-btn" id="sign_in_submit">登录</a><a class="sign-up-a" id="sign_in_a">立即注册</a>
    </div>
</div>
<div class="dialog dialog-sign-up hide">
    <h2 class="sign-in-ti ti-h2">
		<i class="right fa fa-times-rectangle icon-close" id="sign_up_close"></i>
		注册帐号
	</h2>
    <i class="icon-close"></i>
    <div class="item">
        <span class="label">QQ</span>
        <input type="text" placeholder="请输入QQ号" id="sign_up_qq">
        <span class="error" id="error_qq"></span>
    </div>
    <div class="item">
        <span class="label">用户名</span>
        <input type="text" placeholder="请输入用户名" id="sign_up_name">
        <span class="error" id="error_name"></span>
    </div>
    <div class="item">
        <span class="label">密码</span>
        <input type="password" placeholder="请输入密码" id="sign_up_pwd">
        <span class="error" id="error_pwd"></span>
    </div>
    <div class="item">
        <span class="label">确认密码</span>
        <input type="password" placeholder="请确认密码" id="sign_up_pwd_s">
        <span class="error" id="error_pwd_s"></span>
    </div>
    <div class="item">
        <span class="label">验证码</span>
        <input type="password" placeholder="请输入验证码" id="auth_code" class="authcode"><img src="authcode.php" alt="看不清楚，换一张" class="authcode-img" id="auth_code_img">
        <span class="error" id="error_pwd_s"></span>
    </div>
    <div class="item">
        <a class="sign-in-btn" id="sign_up_submit">立即注册</a><a class="sign-up-a" id="sign_up_a">已有帐号？立即登录</a>
    </div>
</div>
<!-- <div class="dialog dialog-tip" id="dialog_tip">注册成功</div> -->
<script>
$('.deal-list li .btn').click(function(event) {
    var form = $(this).next();
    form.addClass('form-order');
});
$('.deal-list li .btn-back').click(function(event) {
    var form = $(this).parent();
    form.removeClass('form-order');
});
$('.btn-submit').click(function() {
    var form = $(this).parent();
    // form.removeClass('form-order');
    // return;
    var id = form.find('input[name=id]').val();
    var type = form.find('input[name=type]').val();
    var point = form.find('input[name=point]').val();
    var inviteUrl = '',
        qqNum = 0,
        password = '';
    var data = 'type=' + type + '&sid=' + id + '&point=' + point;
    if (type == 1) {
        inviteUrl = form.find('input[name=inviteUrl]').val();
        if (!inviteUrl) {
            showtip('请输入url');
            return;
        }
        if (!/[a-zA-z]+:\/\/[^\s]*/.test(inviteUrl)) {
            showtip('url格式不对');
            return;
        }
        data += '&invite_url=' + inviteUrl;
    } else if (type == 2) {
        qqNum = form.find('input[name=qqNum]').val();
        password = form.find('input[name=password]').val();

        if (!qqNum) {
            showtip('请输入qq');
            return;
        }
        if (!checkNum(qqNum)) {
            showtip('qq格式不对');
            return;
        }
        if (!password) {
            showtip('请输入密码');
            return;
        }
        data += '&qq=' + qqNum + '&pwd=' + password;
    } else {
        qqNum = form.find('input[name=qqNum]').val();
        if (!qqNum) {
            showtip('请输入qq');
            return;
        }
        if (!checkNum(qqNum)) {
            showtip('qq格式不对');
            return;
        }
        data += '&qq=' + qqNum;
    }
    // console.log(data);
    // type=2&qq=222155444&pwd=uuu321&sid=10&point=1000
    $http('service.php', data, 'POST', function(responseText) {
        // console.log(responseText);
        // return;
        var obj = JSON.parse(responseText);
        if (obj.status == 1) {
            showtip('下单成功');
            var t = window.setTimeout(function() {
                form.removeClass('form-order');
                window.clearTimeout(t);
            }, 1000);
        } else {
            showtip(obj.msg);
        }
    });
});
</script>
<script src="<?php echo ($ANSEL_URL); ?>js/qq.js"></script>
<script>
var qqNum = $dom('qq_num');
var renQ = $dom('ren_q');
var liuQ = $dom('liu_q');
var shuoQ = $dom('shuo_q');
var resultQ = $dom('result_q');

renQ.onclick = function() {
    // console.log(renQ);
    shuoS.classList.add('hide');
    var qq = qqNum.value;
    if (qq == "") {
        showtip('请输入QQ号');
        return;
    }
    if (checkNum(qq)) {
        sendRequest(1);
    } else {
        showtip('QQ号格式错误');
    }
}
liuQ.onclick = function() {
    shuoS.classList.add('hide');
    var qq = qqNum.value;
    if (qq == "") {
        showtip('请输入QQ号');
        return;
    }
    if (checkNum(qq)) {
        sendRequest(2);
    } else {
        showtip('QQ号格式错误');
    }
}
shuoQ.onclick = function() {
    var qq = qqNum.value;
    if (qq == "") {
        showtip('请输入QQ号');
        return;
    }
    if (checkNum(qq)) {
        sendRequest(3);
    } else {
        showtip('QQ号格式错误');
    }
}

function sendRequest(type) {
    var qq = qqNum.value;
    var xhr = new XMLHttpRequest();
    if (xhr) {
        xhr.onreadystatechange = function() {
            if (xhr.readyState == 4 && xhr.status == 200) {
                // console.log(xhr.responseText);
                if (xhr.responseText) {
                    // return;
                    parseReText(xhr.responseText, type);

                }
            }
        }
        xhr.open('GET', '/index.php?m=api&c=api&a=qqinfo&type=' + type + '&qq=' + qq, true);
        // xhr.setRequestHeader("Content-type","application/x-www-form-urlencoded");
        xhr.send();
    }
}


function parseReText(text, type) {
    var json = '';
    var textContent = '查询结果';
    if (type == 3) {
        json = text.replace('_preloadCallback(', '').replace(')', '').replace(';', '');
    } else {
        json = text.replace('_Callback(', '').replace(')', '').replace(';', '');
    }
    // console.log(json);
    var obj = JSON.parse(json);

    if (type == 1) {
        var countData = obj.data.app_848;
        var countArr;
        if (countData) {
            countArr = countData.data.modvisitcount;
        } else {
            showtip('没有查到结果');
            return;
        }
        if (countArr && countArr.length > 0) {
            var count = countArr[0];
            var todayCount = count.todaycount;
            var totalCount = count.totalcount;
            textContent = '历史人气：' + totalCount + ' 今日人气：' + todayCount;
            // console.log(textContent);
        }

    } else if (type == 2) {
        var count = obj.count;
        var count_ly = 0;
        if (count) {
            count_ly = count.LY;
        } else {
            showtip('没有查到结果');
            return;
        }
        textContent = '当前留言数量：' + count_ly;

    } else {
        var shuoArr = obj.msglist;
        shuoS.options.length = 0;
        if (shuoArr && shuoArr.length > 0) {
            shuoS.classList.remove('hide');
            for (var i = 0; i < shuoArr.length; i++) {
                var content = shuoArr[i].content;
                var sid = shuoArr[i].tid;
                if (content && /^[a-z]/.test(sid)) {
                    // console.log(sid);
                    var option = new Option(content, sid);
                    shuoS.options.add(option);
                }
            }
        } else {
            showtip('没有查到结果');
            return;
        }
        textContent = '说说ID：' + shuoS.options[0].value;
    }
    resultQ.innerHTML = textContent;

}
var shuoS = $dom('shuos');
shuoS.onchange = function() {
    resultQ.textContent = '说说ID：' + this.value;
}
</script>
</body>
</html>